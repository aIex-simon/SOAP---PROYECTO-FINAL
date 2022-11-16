<?php

namespace App\Traits;

use App\Models\Iam\UserProfile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Exception;

trait FilesEmployee
{
    /**
     * set image employee
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param int $employeeId
     * @return array
     */
    private function setImageEmployee(int $employeeId): array
    {
        DB::beginTransaction();
        
        try {
            $disk = $this->getDisk();
            $pathFileUploaded = $disk->putFile(UserProfile::DIRECTORY_IMAGE_PROFILE, $this->file('file'));

            if ($pathFileUploaded === false) {
                return [
                    'status' => false,
                    'message' => 'The image could not be updated.',
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ];
            }

            $profileEmployee = UserProfile::query()
                ->where('user_id', '=', $employeeId)
                ->first();
            if ($profileEmployee->image) {
                $disk->delete($profileEmployee->image);
            }
            $profileEmployee->image = $pathFileUploaded;
            $profileEmployee->save();
            DB::commit();

            return [
                'status' => true,
                'message' => __('The image was updated successfully.'),
                'urlImage' => $disk->url($pathFileUploaded)
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception ( __FUNCTION__ . ': ' . $ex->getMessage() . ', Track: ' . $ex->getTraceAsString());
            Log::error(
                __FUNCTION__ . ': ' . $ex->getMessage() . ', Track: ' . $ex->getTraceAsString()
            );
            return [
                'status' => false,
                'message' => "An error occurred while updating the user's profile image.",
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
    }
}
