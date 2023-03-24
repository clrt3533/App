<?php

namespace Database\Seeders;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Core\Setting\NotificationEvent;
use App\Models\Core\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UpdateSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Model::unguard();

        //Status Seeder
        $statuses = [
            [
                'name' => 'status_pending',
                'type' => 'estimate',
                'class' => 'warning'
            ],
            [
                'name' => 'status_cloned',
                'type' => 'estimate',
                'class' => 'primary'
            ],
        ];
        Status::query()->insert($statuses);



        // Notification seeder
        // remove events
        $remove_events = NotificationEvent::query()->whereIn('name', [
            'roles_created',
            'roles_updated',
            'roles_deleted',
        ])->with('settings')->get();

        foreach ($remove_events as $remove_event) {
            if($remove_event->settings) {
                $remove_event->settings->audiences()->delete();
                $remove_event->settings->delete();
            }
            $remove_event->templates()->sync([]);
            $remove_event->delete();
        }


        $appTypeId = Type::findByAlias('app')->id;
        $events = [
            [
                'name' => 'estimate_sending_attachment',
                'type_id' => $appTypeId,
            ],
        ];
        NotificationEvent::query()->insert($events);


        NotificationEvent::withoutGlobalScope('name')->get()->map(function (NotificationEvent $event) {
            if ($event->name == 'estimate_sending_attachment') {
                $mail = NotificationTemplate::create([
                    'subject' => 'Estimate for {date}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hello {receiver_name}</span><br></p><p>I hope you’re well!
<br>
Please see attached estimate est-{estimate_number}.<br>
Don’t hesitate to contact us if you have any questions.
</p>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            }

        });

        //Role permission seeder
        $appId = Type::findByAlias('app')->id;
        $permissions = [
            //Estimates
            [
                'name' => 'estimate_resend',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],
            [
                'name' => 'view_estimates',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],
            [
                'name' => 'create_estimates',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],
            [
                'name' => 'update_estimates',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],
            [
                'name' => 'delete_estimates',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],
            [
                'name' => 'download_estimate',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],
            [
                'name' => 'clone_invoice',
                'type_id' => $appId,
                'group_name' => 'estimates',
            ],

        ];
        Permission::query()->insert($permissions);

        $clientRole = Role::where('alias', 'client')->first();
        $clientPermission = Permission::whereIn('name',
            [
                'view_quotation',
                'view_estimates',
                'download_estimate',
            ]
        )->get();

        $client = [];
        foreach ($clientPermission as $permission) {
            $client[] = [
                'permission_id' => $permission->id
            ];
        }
        $clientRole->permissions()->syncWithoutDetaching($client);

        Model::reguard();
    }
}
