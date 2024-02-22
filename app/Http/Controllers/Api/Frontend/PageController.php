<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUsGeneral;
use App\Models\ClientLogo;
use App\Models\ContactUs;
use App\Models\ContactUsIssue;
use App\Models\FaqQuestion;
use App\Models\InstructorSupport;
use App\Models\OurHistory;
use App\Models\Policy;
use App\Models\SupportTicketQuestion;
use App\Models\TeamMember;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use ApiStatusTrait; 

    public function aboutUs()
    {
        $data['metaData'] = staticMeta(7);
        $data['aboutUsGeneral'] = AboutUsGeneral::first();
        $data['ourHistories'] = OurHistory::take(4)->get();
        $data['teamMembers'] = TeamMember::all();
        $data['instructorSupports'] = InstructorSupport::all();
        $data['clients'] = ClientLogo::all();
        return $this->success($data, __("Fetch successfully"));
    }

    public function contactUs()
    {
        $data['metaData'] = staticMeta(8);
        $data['contactUsIssues'] = ContactUsIssue::all();
        $data['mapLink'] = get_option('contact_us_map_link');
        return $this->success($data, __("Fetch successfully"));
    }

    public function contactUsStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_us_issue_id' => 'required',
        ]);

        $contact = new ContactUs();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->contact_us_issue_id = $request->contact_us_issue_id;
        $contact->message = $request->message;
        $contact->save();
        return $this->success([], __("Send successfully"));
    }
    
    public function termConditions()
    {
        $data['pageTitle'] = "Terms & Conditions";
        $data['policy'] = Policy::whereType(3)->first();
        return $this->success($data, __("Fetch successfully"));
    }

    public function privacyPolicy()
    {
        $data['pageTitle'] = "Privacy Policy";
        $data['metaData'] = staticMeta(10);
        $data['policy'] = Policy::whereType(1)->first();
        return $this->success($data, __("Fetch successfully"));
    }

    public function cookiePolicy()
    {
        $data['pageTitle'] = "Cookie Policy";
        $data['metaData'] = staticMeta(11);
        $data['policy'] = Policy::whereType(2)->first();
        return $this->success($data, __("Fetch successfully"));
    }
    
    public function refundPolicy()
    {
        $data['pageTitle'] = "Refund Policy";
        $data['metaData'] = staticMeta(14);
        $data['policy'] = Policy::whereType(4)->first();
        return $this->success($data, __("Fetch successfully"));
    }

    public function supportTicketFAQ()
    {
        $data['pageTitle'] = 'Support Ticket';
        $data['metaData'] = staticMeta(9);
        $data['faqQuestions'] = SupportTicketQuestion::all();
        return $this->success($data, __("Fetch successfully"));
    }

    public function faq()
    {
        $data['pageTitle'] = 'FAQ';
        $data['metaData'] = staticMeta(12);
        $data['faqs'] = FaqQuestion::all();
        return $this->success($data, __("Fetch successfully"));
    }
}