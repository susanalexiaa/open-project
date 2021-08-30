<?php

namespace App\Jobs;

use App\Domains\Directory\Models\Emailuid;
use App\Domains\Integration\Models\Integration;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Webklex\PHPIMAP\ClientManager;
use App\Helpers\LeadDetailsFromMail;
use Carbon\Carbon;
use App\Domains\Contractor\Models\Contractor;
use App\Domains\Contractor\Models\ContractorLead;
use App\Helpers\PhoneHelper;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;

class GettingActualIntegrationsOfMailTracker implements ShouldQueue
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
        $cm = new ClientManager();

        $integrations = Integration::where('type', 'Трекер почты')->where('is_active', true)->get();
        foreach ($integrations as $integration) {
            $client = $cm->make([
                'host' => trim($integration->operator->address),
                'port' => trim($integration->operator->port),
                'encryption' => trim($integration->operator->type),
                'validate_cert' => true,
                'username' => trim($integration->login),
                'password' => trim($integration->password),
                'protocol' => 'imap'
            ]);

            $client->connect();

            $folder_paths = ['INBOX'];
            $folders = [];

            foreach ($folder_paths as $folder_path) {
                $folders[] = $client->getFolderByPath($folder_path);
            }

            $since_date = is_null($integration->last_integration) ? Carbon::now()->subDays(2) : $integration->last_integration;

            $messages = collect();

            foreach ($folders as $folder) {
                $messages = $messages->merge(
                    $folder->query()
                        ->since($since_date)
                        ->get()
                );
            }

            $email_bodies = [];

            foreach ($messages as $message) {
                $from = $message->getHeader()->from[0]->mail;
                $allowed_emails = explode(',', str_replace(' ', '', $integration->allowed_addresses) );
                $uid = $message->get("uid");

                if ( in_array($from, $allowed_emails) && !Emailuid::isExist($uid, $integration->login) ) {
                    $email_bodies[] = [
                        'body' => trim( strip_tags( $message->getTextBody() ?
                                                $message->getTextBody() :
                                                $message->getHTMLBody()) ),
                        'uid' => $uid];
                }
            }

            foreach ($email_bodies as $email_body) {
                $data = LeadDetailsFromMail::getDetailsFromBody($email_body['body']);

                $phone = PhoneHelper::getStandartisedNumber($data['phone']);

                $contractor = Contractor::query()->where('phone', $phone);

                if ($contractor->exists()) {
                    $contractor = $contractor->first();
                } else {
                    $contractor = Contractor::query()->create([
                        'title' => $data['name'],
                        'email' => $data['email'],
                        'phone' => $phone
                    ]);
                }

                $source = LeadSource::query()->where('name', $data['name']);
                if ($source->exists()) {
                    $source = $source->first();
                } else {
                    $source = LeadSource::query()->find(2);
                }

                $is_hot = $data['price'] >= 20000;

                $lead = Lead::query()->create([
                    'is_hot' => $is_hot,
                    'user_id' => 1,
                    'source_id' => $source->id,
                    'status_id' => 1,
                    'responsible_id' => $integration->responsible_id,
                    'team_id' => $integration->team_id
                ]);

                Emailuid::query()->create([
                    'emailuid' => $email_body['uid'],
                    'email' => $integration->login
                ]);
                $lead->setDescription($email_body['body']);

                ContractorLead::query()->create([
                    'contractor_id' => $contractor->id,
                    'lead_id' => $lead->id
                ]);

            }

            $integration->update(['last_integration' => Carbon::now()]);
        }
    }
}
