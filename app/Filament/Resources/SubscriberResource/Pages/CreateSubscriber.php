<?php

namespace App\Filament\Resources\SubscriberResource\Pages;

use App\Filament\Resources\SubscriberResource;
use App\Models\tenant;
use App\Models\Tenant\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateSubscriber extends CreateRecord
{
    protected static string $resource = SubscriberResource::class;

    protected function afterCreate(): void
    {
        $subscriber = $this->record;
        /** START - Create Tenant **/
        $tenantID = trim(Str::slug($subscriber->name, '_'));
        $tenantID = strtolower($tenantID);
        $tenantID = Str::snake($tenantID);
        // dd($subscriber->id);

        $tenant = Tenant::create(
            [
                'id' => $tenantID,
                'subscriber_id' => $subscriber->id
            ]
        );
        // Assign tenant to subscriber
        $subscriber->tenant_id = $tenant->id;
        $subscriber->save();
        /** END - Create Tenant **/

        // TODO: You need to create subscription here

        /** START - Subdomain setup **/
        $domainName = (app()->isLocal()) ? ".localhost" : ".digitilez.com"; // digitilez.com will be changed once we know the real domain
        //        $domainName = $domainName.'/admin/login';
        //        $subdomainPart = strtolower( Str::snake(trim($subscriber->business_name))); // NB: domain must can be added to the form so admin can insert it
        $subdomainPart = strtolower(trim(Str::slug($subscriber->business_name))); // NB: domain must can be added to the form so admin can insert it
        $subdomain = $subdomainPart . $domainName;
        $tenant->createDomain([
            'domain' => $subdomain,
        ]);
        /** END - Subdomain setup **/

        $tenant1 = $subscriber->tenant;
        $tenant1->run(function () use ($subscriber) {
            User::create(
                [
                    'name' => $subscriber->name,
                    'email' => $subscriber->email,
                    'password' => Hash::make($subscriber->password),
                    'email_verified_at' => now(),
                    'login' => $subscriber->email
                ]
            );
        });
    }
}
