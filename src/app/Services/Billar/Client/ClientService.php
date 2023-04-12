<?php /** @noinspection PhpFieldAssignmentTypeMismatchInspection */


namespace App\Services\Billar\Client;


use App\Helpers\Core\Traits\HasWhen;
use App\Mail\Billar\ClientInvitationMail;
use App\Models\Billar\Client\Client;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Services\Billar\ApplicationBaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ClientService extends ApplicationBaseService
{
    use HasWhen;

    public User $user;

    public function __construct(Client $client)
    {
        $this->model = $client;
    }

    public function setValidation(): self
    {
        validator(request()->all(), [
            'client_number' => 'required|max:191|unique:clients,client_number',
            'full_name' => 'required|max:191',
            'email' => 'required|email'
        ], [
            'full_name.required' => 'The name field is required.'
        ])->validate();

        return $this;
    }

    public function setUpdateValidation(): self
    {
        $id = $this->model->id ?: '';
        validator(request()->all(), [
            'client_number' => 'required|max:191|unique:clients,client_number,' . $id,
            'full_name' => 'required|max:191',
            'email' => 'required|email',
        ], [
            'full_name.required' => 'The name field is required.'
        ])->validate();

        return $this;

    }

    public function setUserValidation(): self
    {
        $userId = $this->model->user_id ?: '';
        validator(request()->all(), [
            'email' => 'required|email|unique:users,email,' . $userId,
        ])->validate();

        return $this;
    }

    public function saveUser($password)
    {
        $this->user = $this->model->user()
            ->create($this->userInfo($password));
        return $this;
    }

    public function roleAssign()
    {
        $role = Role::where('alias', 'client')->first();
        $this->user->roles()->sync($role);
        return $this;
    }

    public function clientStore()
    {
        $this->user->client()->create(request()->all());
        return $this;
    }

    public function updateUser(): self
    {
        $this->model->user()->update([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
        ]);
        return $this;
    }

    public function userInfo($password): array
    {
        return [
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'status_id' => Status::findByNameAndType('status_active')->id,
            'password' => Hash::make($password),
        ];

    }

    public function profileStore(): self
    {
        if (request()->get('phone') || request()->get('address') || request()->get('vat_number')) {
            $this->user->profile()->create([
                'contact' => request('phone'),
                'address' => request('address'),
                'vat_number' => request('vat_number'),
            ]);
        }
        return $this;
    }

    public function clientInvitationMail($password): ClientService
    {
        Mail::to(request()->get('email'))
            ->send(
                (new ClientInvitationMail($this->user, $password))
                    ->onQueue('high'));
        return $this;
    }

    public function updateProfile(): self
    {

        if (request()->get('phone') || request()->get('address') || request()->get('vat_number')) {
            $checkUser = $this->model->load('user');

            Profile::query()->updateOrCreate([
                'user_id' => $checkUser->user->id
            ], array_merge(
                    ['user_id' => $checkUser->user->id],
                    [
                        'contact' => request('phone'),
                        'address' => request('address'),
                        'vat_number' => request('vat_number'),
                    ]
                )
            );

        }
        return $this;
    }

    public function userDelete(): self
    {
        $this->model->user()->delete();
        return $this;
    }

    public function randomPassword()
    {
        $password = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
        return substr($password, 0, 8);
    }

}