<?php

namespace App\Mail\Billar;

use App\Mail\Tag\EstimateTag;
use App\Models\Billar\Estimates\Estimate;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstimateAttachmentMail extends Mailable
{
    use Queueable, SerializesModels;

    protected Estimate $estimate;


    public string $template;
    protected $notificationEventName;

    public function __construct(Estimate $estimate, $notificationEventName = null)
    {
        $tag = new EstimateTag($estimate);
        $this->notificationEventName = $notificationEventName;

        $this->estimate = $estimate;

        $template = $this->template();

        $this->template = optional($template)->parse(
            method_exists($tag, 'invoiceGenerate') ? $tag->estimateGenerate() :
                [
                    '{estimate_number}' => optional($this->estimate)->estimate_number,
                    '{receiver_name}' => optional($this->estimate)->client->full_name,
                    '{app_name}' => config('app.name'),
                ]
        );

        $this->subject = optional($template)->parseSubject(
            method_exists($tag, 'invoiceGenerate') ? $tag->estimateGenerate() : [
                '{date}' => dateFormat(optional($this->estimate)->date)
            ]
        );
    }

    public function build(): EstimateAttachmentMail
    {
        return $this->view('notification.mail.user.template', [
            'template' => $this->template
        ])->subject($this->subject)
            ->attach(storage_path('app/public/pdf/estimate_' . $this->estimate->id . '.pdf'), [
                'as' => 'estimate.pdf',
                'mime' => 'application/pdf',
            ]);
    }

    public function template(): \App\Models\Core\Notification\NotificationTemplate
    {
        return NotificationTemplateHelper::new()
            ->on($this->notificationEventName ?? 'estimate_sending_attachment')
            ->mail();
    }
}
