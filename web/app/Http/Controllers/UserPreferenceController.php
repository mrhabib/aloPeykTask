<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/preferences",
     *     summary="Get user preferences",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show()
    {
        $preference = Auth::user()->preference();
        return response()->json($preference);
    }

    /**
     * @OA\Post(
     *     path="/user/preferences",
     *     summary="Update user preferences",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="notification_preference", type="string", enum={"email", "sms"}, example="email")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function update(Request $request)
    {
        $request->validate([
            'notification_preference' => 'required|in:email,sms',
        ]);

        $user = Auth::user();
        $preference = $user->preference()->updateOrCreate(
            ['user_id' => $user->id],
            ['notification_preference' => $request->notification_preference]
        );

        return response()->json($preference, 200);
    }
}
