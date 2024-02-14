<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmit;
use App\Models\Course;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\TicketMessages;
use App\Models\TicketPriority;
use App\Models\TicketRelatedService;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Auth;

class SupportTicketController extends Controller
{
    use General, ImageSaveTrait;

    protected $modalTicket, $modelTicketDepartment, $modelTicketPriority, $modelTicketService;

    public function __construct(Ticket $modalTicket,TicketDepartment $modelTicketDepartment, TicketPriority $modelTicketPriority, TicketRelatedService $modelTicketService)
    {
        $this->modalTicket = new CRUD($modalTicket);
        $this->modelTicketDepartment = new CRUD($modelTicketDepartment);
        $this->modelTicketPriority = new CRUD($modelTicketPriority);
        $this->modelTicketService = new CRUD($modelTicketService);
    }


    public function ticketIndex()
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket List';
        $data['navSupportTicketParentActiveClass'] = 'mm-active';
        $data['subNavSupportTicketIndexActiveClass'] = 'mm-active';
        $data['tickets'] = $this->modalTicket->getOrderById('DESC', 25);

        return view('admin.support_ticket.index', $data);
    }

    public function ticketOpen()
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket List';
        $data['navSupportTicketParentActiveClass'] = 'mm-active';
        $data['subNavSupportTicketOpenActiveClass'] = 'mm-active';
        $data['tickets'] = Ticket::where('status', 1)->paginate(25);

        return view('admin.support_ticket.open', $data);
    }

    public function ticketShow($uuid)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket Replies';
        $data['navSupportTicketParentActiveClass'] = 'mm-active';
        $data['subNavSupportTicketIndexActiveClass'] = 'mm-active';
        $data['ticket'] = $this->modalTicket->getRecordByUuid($uuid);
        $data['ticketMessages'] = TicketMessages::where('ticket_id', $data['ticket']->id)->get();
        $data['last_message'] = TicketMessages::where('ticket_id', $data['ticket']->id)->whereNotNull('sender_user_id')->latest()->first();

        return view('admin.support_ticket.details', $data);
    }


    public function ticketDelete($uuid)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        TicketMessages::where('ticket_id', $ticket->id)->get()->map(function ($q) {
            $this->deleteFile($q->file);
            $q->delete();
        });
        $this->deleteFile($ticket->file);
        $ticket->delete();
        return redirect()->back();
    }

    public function changeTicketStatus(Request $request)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $ticket = Ticket::findOrFail($request->id);
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json([
            'data' => 'success',
        ]);
    }

    public function messageStore(Request $request)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'message' => 'required',
            'file' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $message = new TicketMessages();
        $message->ticket_id = $request->ticket_id;
        $message->reply_admin_user_id = auth()->id();
        $message->message = $request->message;

        if ($request->hasFile('file')) {
            $message->file = $this->saveImage('ticket_message', $request->file, 'null', 'null');
        }

        $message->save();

        return redirect()->back();
    }



    public function Department()
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket Department Field';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavSupportSettingsActiveClass'] = 'mm-active';
        $data['supportDepartmentActiveClass'] = 'active';
        $data['departments'] = TicketDepartment::all();

        return view('admin.application_settings.support_ticket.ticket-department-list', $data);
    }

    public function DepartmentStore(Request $request)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required|max:255'
        ]);

        if ($request->id) {
            $item = TicketDepartment::find($request->id);
            $msg = 'Updated Successful';
            if (!$item) {
                $item = new TicketDepartment();
                $msg = 'Created Successful';
            }

        } else {
            $item = new TicketDepartment();
            $msg = 'Created Successful';
        }

        $item->name = $request->name;
        $item->save();

        $this->showToastrMessage('success', __($msg));
        return redirect()->back();
    }

    public function departmentDelete($uuid)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $this->modelTicketDepartment->deleteByUuid($uuid);
        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }

    public function Priority()
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket Priority Field';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavSupportSettingsActiveClass'] = 'mm-active';
        $data['supportPriorityActiveClass'] = 'active';
        $data['priorities'] = TicketPriority::all();

        return view('admin.application_settings.support_ticket.ticket-priority-list', $data);
    }

    public function PriorityStore(Request $request)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required|max:255'
        ]);

        if ($request->id) {
            $item = TicketPriority::find($request->id);
            $msg = 'Update Successful';
            if (!$item) {
                $item = new TicketPriority();
                $msg = 'Created Successful';
            }

        } else {
            $item = new TicketPriority();
            $msg = 'Created Successful';
        }

        $item->name = $request->name;
        $item->save();

        $this->showToastrMessage('success', __($msg));
        return redirect()->back();
    }

    public function priorityDelete($uuid)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $this->modelTicketPriority->deleteByUuid($uuid);
        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }


    public function RelatedService()
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket Related Service Field';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavSupportSettingsActiveClass'] = 'mm-active';
        $data['supportRelatedActiveClass'] = 'active';
        $data['services'] = TicketRelatedService::all();

        return view('admin.application_settings.support_ticket.ticket-related-service-list', $data);
    }

    public function RelatedServiceStore(Request $request)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required|max:255'
        ]);

        if ($request->id) {
            $msg = 'Updated Successful';
            $item = TicketRelatedService::find($request->id);
            if (!$item) {
                $item = new TicketRelatedService();
                $msg = 'Created Successful';
            }

        } else {
            $item = new TicketRelatedService();
            $msg = 'Created Successful';
        }

        $item->name = $request->name;
        $item->save();

        $this->showToastrMessage('success', __($msg));
        return redirect()->back();
    }

    public function relatedServiceDelete($uuid)
    {
        if (!Auth::user()->can('support_ticket')) {
            abort('403');
        } // end permission checking

        $this->modelTicketService->deleteByUuid($uuid);
        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }
}
