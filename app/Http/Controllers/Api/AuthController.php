<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseApiController
{
    /** POST /auth/register */
    public function register(RegisterRequest $req)
    {
        $data = $req->validated();
        $data['password_hash'] = Hash::make($data['password']);
        unset($data['password']);

        // rôle par défaut
        if (empty($data['role'])) $data['role'] = 'employee';

        $user = User::create($data);

        // Abilities par défaut selon rôle (simple RBAC)
        $abilities = $this->defaultAbilitiesForRole($user->role);

        $token = $user->createToken(
            name: $req->input('device', 'api-client'),
            abilities: $abilities
        )->plainTextToken;

        return response()->json([
            'message' => 'Registered',
            'data' => [
                'user'  => [
                    'id' => $user->id,
                    'name' => $user->fullName(),
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'token' => $token,
                'abilities' => $abilities,
            ]
        ], 201);
    }

    /** POST /auth/login */
    public function login(LoginRequest $req)
    {
        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password_hash)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Optionnel: bloquer les inactifs
        if (!$user->is_active) {
            return response()->json(['message' => 'Account disabled'], 403);
        }

        // Abilities (si précisées, on les prend; sinon on calcule par rôle)
        $abilities = $req->filled('abilities')
            ? array_values(array_unique($req->abilities))
            : $this->defaultAbilitiesForRole($user->role);

        // Révoquer les anciens tokens du même device (bonne pratique)
        $deviceName = $req->input('device', 'api-client');
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken(
            name: $deviceName,
            abilities: $abilities
        )->plainTextToken;

        return $this->ok([
            'user'      => [
                'id'    => $user->id,
                'name'  => $user->fullName(),
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'token'     => $token,
            'abilities' => $abilities,
        ], 'Logged in');
    }

    /** POST /auth/logout */
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()?->delete();
        return $this->ok(null, 'Logged out');
    }

    /** POST /auth/logout-all */
    public function logoutAll(Request $req)
    {
        $req->user()->tokens()->delete();
        return $this->ok(null, 'Logged out from all devices');
    }

    /** GET /auth/me */
    public function me(Request $req)
    {
        $u = $req->user();
        return $this->ok([
            'id'    => $u->id,
            'name'  => $u->fullName(),
            'email' => $u->email,
            'role'  => $u->role,
            'abilities' => $req->user()->currentAccessToken()?->abilities ?? [],
        ]);
    }

    /** POST /auth/rotate-token (réémet un token et révoque l’actuel) */
    public function rotateToken(Request $req)
    {
        $u = $req->user();
        $abilities = $u->currentAccessToken()?->abilities ?? $this->defaultAbilitiesForRole($u->role);

        // Supprime l’actuel et recrée
        $currentName = $u->currentAccessToken()?->name ?? 'api-client';
        $u->currentAccessToken()?->delete();

        $new = $u->createToken($currentName, $abilities)->plainTextToken;

        return $this->ok(['token' => $new, 'abilities' => $abilities], 'Rotated');
    }

    /** Map simple rôle -> abilities (à affiner selon tes besoins) */
    private function defaultAbilitiesForRole(string $role): array
    {
        return match ($role) {
            'super_admin' => ['*'],
            'hr'          => [
                'users:read','users:update','attendance:read','attendance:update',
                'schedules:read','schedules:update','absences:read','absences:update',
                'reports:read','reports:create','settings:read','settings:update'
            ],
            'manager'     => [
                'users:read','attendance:read','attendance:update',
                'schedules:read','absences:read','absences:update'
            ],
            'auditor'     => ['users:read','attendance:read','schedules:read','absences:read','reports:read'],
            default       => ['attendance:read','schedules:read','absences:read'],
        };
    }
}
