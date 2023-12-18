<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Models\Tenant\WebsiteFaq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class FaqController extends Controller
{
    public function getListOfFaqPublic(Request $request)
    {
        $data = $request->all();
        $companyId = $data['company_id'];

        if ($companyId) {
            $faq = WebsiteFaq::where('company_id', $companyId)->get();
        } else {
            $faq = WebsiteFaq::all();
        }

        $faqList = [];

        if ($faq->isNotEmpty()) {
            foreach ($faq as $f) {
                $values = [
                    "question" => $f->name,
                    "answer" => $f->answer,
                    "banner" => "/web/content/" . ($f->banner ?? ''),
                ];
                $faqList[] = $values;
            }

            return response()->json([
                'response' => $faqList,
                'message' => 'List of FAQ'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No FAQ found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
