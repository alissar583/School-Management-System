<?php
namespace App\Traits;

trait generalTrait{
    public function returnError($errorNumber, $message)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errorNumber,
            'message' => $message
        ]);
    }
    public function returnErrorMessage($message, $statusNum)
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ],$statusNum);
    }


    public function returnSuccessMessage($message= "")
    {
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function returnData($key, $value, $message = "")
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            $key => [$value]
        ]);
    }
    public function returnAllData($key, $value, $message = "") {
        return response()->json([
            'status' => true,
            'message' => $message,
            $key => $value
        ]);
    }
    public function returnLoginData($role, $id, $value, $message = "") {
        return response()->json([
            'status' => true,
            'message' => $message,
            'role' => $role,
            'id' => $id,
            'key' => $value
        ]);
    }

    public function returnMark($mark, $message = "") {
        return response()->json([
            'status' => true,
            'message' => $message,
            'mark' => $mark
        ]);
    }
}









