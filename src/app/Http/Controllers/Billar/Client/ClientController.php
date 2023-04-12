<?php

namespace App\Http\Controllers\Billar\Client;

use App\Exceptions\GeneralException;
use App\Filters\Billar\Client\ClientFilter;
use App\Http\Controllers\Controller;
use App\Jobs\SendMessageJob;
use App\Models\Billar\Client\Client;
use App\Models\Billar\Invoice\Invoice;
use App\Services\Billar\Client\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(ClientService $service, ClientFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->with([
                'country:id,name',
                'user' => function ($query) {
                    $query->with('profilePicture', 'status:id,name,class', 'profile:id,user_id,address,contact');
                }])
            ->filters($this->filter)
            ->orderBy('id', request('orderBy', 'desc'))
            ->paginate(request('per_page', 10));
    }

    public function store(Request $request)
    {
        $password = $this->service->randomPassword();
        $this->service
            ->setValidation()
            ->setUserValidation()
            ->saveUser($password)
            ->roleAssign()
            ->clientStore()
            ->profileStore()
            ->clientInvitationMail($password);
        SendMessageJob::dispatch('onboard-message',$this->service->user->profile->contact);
        return created_responses('clients');

    }

    public function show($id)
    {
        return $this->service->with([
            'country:id,name',
            'user' => function ($query) {
                $query->with('profilePicture', 'profile:id,user_id,address,contact,vat_number');
            }])->find($id);
    }

    public function update(Request $request, Client $client)
    {
        $this->service
            ->setModel($client)
            ->setUpdateValidation()
            ->setUserValidation()
            ->updateUser()
            ->setAttributes($request->all())
            ->update()
           ->updateProfile() ;

        return updated_responses('clients');
    }

    public function destroy(Client $client)
    {
        $invoiceExist = $client->load(['user' => function ($query) {
            $query->withCount('invoices');
        }]);

        if (optional($invoiceExist->user)->invoices_count > 0)
            throw new GeneralException(trans('default.this_client_has_an_invoice_record'));

        $this->service
            ->setModel($client)
            ->userDelete()
            ->delete();

        return deleted_responses('clients');
    }

    public function clientInvoices($id): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $userId = $this->service->find($id)->user_id;
        return Invoice::with(['status', 'client'])
            ->where('client_id', $userId)
            ->orderBy('id', request()->get('orderBy'))
            ->paginate(request('per_page', 10));
    }
}
