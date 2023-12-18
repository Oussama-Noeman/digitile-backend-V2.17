<?php

namespace  App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;

use App\Models\Tenant\CareerInformation;
use App\Models\Tenant\HrApplication;
use App\Models\Tenant\HrJob;
use App\Utils\Base64;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class JobController extends Controller
{
    public function getCareerInformation(Request $request)
    {

        $productInfo = new CustomHelper(); // Adjust the instantiation based on your actual implementation

        $requestData = $request->all();

        if (isset($requestData['company_id'])) {
            $careerInformation = CareerInformation::where('company_id', $requestData['company_id'])->get();
        } else {
            $careerInformation = CareerInformation::limit(1)->get();
        }

        if ($careerInformation->isNotEmpty()) {
            $careerInfo = $careerInformation->first();

            $response = [
                "title" => $careerInfo->title ?? "",
                "description" => $productInfo->changeParagToLine($careerInfo->description) ?? "",
                "title1" => $careerInfo->title1 ?? "",
                "icon1" => "/web/content/" . $careerInfo->icon1 ?? "",
                "description1" => $productInfo->changeParagToLine($careerInfo->description1) ?? "",
                "title2" => $careerInfo->title2 ?? "",
                "icon2" => "/web/content/" . $careerInfo->icon2 ?? "",
                "description2" => $productInfo->changeParagToLine($careerInfo->description2) ?? "",
                "title3" => $careerInfo->title3 ?? "",
                "icon3" => "/web/content/" . $careerInfo->icon3 ?? "",
                "description3" => $productInfo->changeParagToLine($careerInfo->description3) ?? "",
                "vacancies_title" => $careerInfo->vacancies_title ?? "",
                "vacancies_description" => $productInfo->changeParagToLine($careerInfo->vacancies_description) ?? ""
            ];
            return response()->json([
                'response' => $response,
                'message' => 'Career information Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => ' No Data Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function jobApplyForm(Request $request)
    {
        $requestData = $request->all();
        $validate = Validator::make($request->all(), [
            'job_id' => 'required|integer',
            'full_name' => 'required|string',
            'email' => 'required|string',
            'message' => 'required|string',
            'mobile' => 'required|string',
            'file' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }

        $job = HrJob::find($requestData['job_id']);

        if ($job) {
            // Assuming 'ProductInfo' is a class with the 'is_valid_email' method
            $detail = new CustomHelper();
            if (!$detail->isValidEmail($requestData['email'])) {
                return response()->json([
                    'response' => [],
                    'message' => 'email not valid!!'
                ], Response::HTTP_NOT_FOUND);
            }

            // Create applicant
            $applicant = [
                'job_id' => $job->id,
                'partner_name' => $requestData['full_name'],
                'name' => $requestData['full_name'],
                'email_from' => $requestData['email'],
                'description' => $requestData['message'],
                'partner_mobile' => $requestData['mobile'],
            ];
            $file = $requestData['file'];
            $directory = 'applications/job/';
            $data = Base64::getDecode($directory, $file);
            $applicant['file'] = $data['file_path'];


            $hrApplicant = HrApplication::create($applicant);

            if ($hrApplicant) {
                return response()->json([
                    'response' => [],
                    'message' => 'Form created successfully'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Form not created'
                ], Response::HTTP_OK);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No Data Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getJobPositions(Request $request)
    {
        $helper = new CustomHelper();
        $validate = Validator::make($request->all(), [
            'company_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $requestData = $request->all();

            $jobs = HrJob::where('is_published', true)
                ->where('is_cv', false);

            if ($requestData['company_id']) {
                $jobs->where('company_id', $requestData['company_id']);
            }

            $jobs = $jobs->get();

            $jobList = [];

            foreach ($jobs as $job) {
                $stateName = $job->address->state->name ?? "";
                $cityName = $job->address->city->name ?? "";
                $streetName = $job->address->street ?? "";

                $jobList[] = [
                    "job_id" => $job->id,
                    "job_name" => $job->name ?: "",
                    "job_description" => $helper->changeParagToLine($job->description) ?: "",
                    "address" => $stateName . " " . $cityName . " " . $streetName,
                    "image" => "/web/content/" . $job->image ?: "",
                    "created_at" => $job->created_at,
                ];
            }

            if (!empty($jobList)) {

                return response()->json([
                    'response' => $jobList,
                    'message' => 'Job positions Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No Data Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
    public function cvApplyForm(Request $request)
    {
        $requestData = $request->all();
        $validate = Validator::make($request->all(), [
            'job_id' => 'required|integer',
            'full_name' => 'required|string',
            'email' => 'required|string',
            'message' => 'required|string',
            'mobile' => 'required|string',
            'file' => 'required',
            // 'file' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $job = HrJob::find($requestData['job_id']); // Replace with your actual configuration key

            if ($job) {
                $applicantData = [
                    "job_id" => $job->id,
                    "partner_name" => $requestData['full_name'],
                    "name" => $requestData['full_name'],
                    "email_from" => $requestData['email'],
                    "description" => $requestData['message'],
                    "partner_mobile" => $requestData['mobile'],
                ];

                // $applicantData['file'] = $request->file('file')->store('application_files', 'public');

                //te5bis start 
                $file = $requestData['file'];
                $directory = 'applications/cv/';
                $data = Base64::getDecode($directory, $file);
                $applicantData['file'] = $data['file_path'];
                //te5bis end

                $hrApplicant = HrApplication::create($applicantData);

                if ($hrApplicant) {
                    return response()->json([
                        'response' => [],
                        'message' => 'Form created successfully'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'Form not created'
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No Data Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
}
