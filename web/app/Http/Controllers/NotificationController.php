<?php

namespace App\Http\Controllers;

use App\Notifications\NotificationService;
use Illuminate\Http\Request;

use App\Models\User;
/**
 * @OA\Info(title="My API", version="1.0.0")
 */
class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @OA\Post(
     *     path="/notifications/send",
     *     summary="Send a notification",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="message", type="string", example="Hello, this is a test notification"),
     *             @OA\Property(property="type", type="string", enum={"email", "sms"}, example="sms")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=500, description="Failure")
     * )
     */
    public function send(Request $request, $type = null)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $user = User::find($request->user_id);
        $message = $request->message;

        // If type is specified in the route, use it; otherwise, use null to indicate no preference
        $this->notificationService->sendNotification($user, $message, $type);

        return response()->json(['status' => 'success'], 200);
    }
}

