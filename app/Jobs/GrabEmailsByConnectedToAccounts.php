<?php

namespace App\Jobs;

use App\Domains\Contractor\Models\Contractor;
use App\Domains\Contractor\Models\EmailFromAccountToContractor;
use App\Domains\Directory\Models\AccountEmail;
use App\Domains\Integration\Models\ConnectedEmailAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpImap\Mailbox;
use Carbon\Carbon;

class GrabEmailsByConnectedToAccounts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $integrations = $this->getIntegrations();

        foreach ($integrations as $integration){
            $login = trim($integration->login);

            if( is_null($integration) ) return;

            $mailbox = new Mailbox(
                '{'.trim($integration->operator->address).':'.trim($integration->operator->port).'/imap/'.trim($integration->operator->type).'}',
                $login,
                trim($integration->password),
            );

            $since = $integration->last_integration->format('Ymd');

            $folders = $mailbox->getMailboxes('*');
            $mails_ids = [];

            foreach ($folders as $folder) {
                $mailbox->switchMailbox($folder['fullpath']);
                $mails_ids = $mailbox->searchMailbox('SINCE "'.$since.'"');

                if(!$mails_ids) continue;

                foreach ($mails_ids as $num) {
                    $head = $mailbox->getMailHeader($num);

                    $from = $head->fromAddress;
                    $to = array_key_first($head->to);

                    $record = AccountEmail::query()->where([
                        ['email', $login],
                        ['emailuid', $num],
                        ['folder', $folder['shortpath'] ]
                    ]);

                    if( !$record->exists() ){
                        AccountEmail::query()->create([
                            'email' => $login,
                            'emailuid' => $num,
                            'folder' => $folder['shortpath']
                        ]);

                        $isOutcoming = $from == $login;

                        $contractor_email = $isOutcoming ? $to : $from;

                        $contractor = Contractor::findByEmail($contractor_email);

                        if( $contractor->exists() ){
                            $contractor = $contractor->first();
                            $mail = $mailbox->getMail($num, false);

                            EmailFromAccountToContractor::query()->create([
                                'contractor_id' => $contractor->id,
                                'title' => $head->subject,
                                'content' => !$mail->textHtml ? $mail->textPlain : $mail->textHtml,
                                'type' => $isOutcoming ? 'Outcoming' : 'Incoming',
                                'made_at' => Carbon::parse($head->date)
                            ]);
                        }
                    }
                }
            }
            $integration->last_integration = Carbon::now();
            $integration->save();
        }
    }

    protected function getIntegrations(){
        return ConnectedEmailAccount::query()->where('is_active', true)->get();
    }
}
