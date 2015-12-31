<?php

class AccountSeeder extends Seeder
{

    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));
        $account = new Account;
        $account->title = 'Surge';
        $account->active = true;
        $account->name = 'Surge';
        $account->address = '11820 Northup Way';
        $account->address_2 = 'Suite E-200';
        $account->city = 'Bellevue';
        $account->state = 'WA';
        $account->phone = '866-991-6883';
        $account->country = 'US';
        $account->zipcode = '98005';
        $account->email = 'jkuchynka+surge@surgeforward.com';
        $account->auto_renew = true;
        $account->expiration_date = $expiration;
        $account->payment_type = 'CC';
        $account->token = 12345;
        $account->yearly_payment = false;
        $account->save();

        $this->command->info('Added Surge account');

        // Give access to all modules
        $modules = Module::all();
        foreach ($modules as $module) {
            $account->modules()->save($module);
        }

        $this->command->info('Granted Surge account access to all modules');

        // Save account's subscription
        $subscription = Subscription::where('subscription_level', 1)->first();
        $sub = new AccountSubscription;
        $sub->account_id = $account->id;
        $sub->subscription_level = 3;
        $sub->licenses = $subscription->licenses;
        $sub->monthly_price = $subscription->monthly_price;
        $sub->annual_discount = $subscription->annual_discount;
        $sub->training = $subscription->training;
        $sub->features = $subscription->features;
        $sub->save();

        $this->command->info('Created Surge account subscription');

        // Attach builtin roles
        $roles = Role::whereNull('account_id')->where('name', '<>', 'global_admin')->get();
        foreach ($roles as $bRole) {
            $role = new AccountRole;
            $role->account_id = $account->id;
            $role->name = $bRole->name;
            $role->display_name = $bRole->display_name;
            $role->status = 1;
            $role->global = 0;
            $role->builtin = 1;
            $role->deletable = 0;
            $role->save();
            // Copy default role permissions to newly created role
            $perms = $bRole->perms()->get();
            if ($perms) {
                $attach = array();
                foreach ($perms as $perm) {
                    $attach[] = $perm->id;
                }
                $role->perms()->sync($attach);
            }
        }

        $this->command->info('Attached builtin roles to Surge account');

        // Custom role
        $role = new AccountRole;
        $role->account_id = $account->id;
        $role->name = 'custom';
        $role->display_name = 'Custom';
        $role->status = 1;
        $role->global = 0;
        $role->builtin = 0;
        $role->deletable = 1;
        $role->save();

        $this->command->info('Attached custom role to Surge account');

        // Save content settings
        $settings = new AccountContentSettings;
        $settings->account_id = $account->id;
        $settings->include_name = array(
            'enabled' => 1,
            'content_types' => array(
                'audio', 'ebook', 'google_drive', 'photo', 'video'
            )
        );
        $settings->allow_edit_date = array(
            'enabled' => 1,
            'content_types' => array(
                'blog_post', 'email', 'landing_page', 'twitter', 'whitepaper'
            )
        );
        $settings->keyword_tags = array(
            'enabled' => 1,
            'content_types' => array(
                'case_study', 'facebook_post', 'linkedin', 'salesforce_asset'
            )
        );
        $settings->publishing_guidelines = 'Publishing guidelines here';
        $settings->persona_columns = array('suspects', 'prospects', 'leads', 'opportunities', 'custom');
        $settings->personas = array(
            array(
                'name' => 'CMO',
                'columns' => array(
                    'Concerned with learning more about industry best practices and staying ahead of the curve. Results oriented, needs materials that are shore and sweet with solid, actionable take aways.',
                    'Looking for specific information to pass along to VP or Director for actions. Has interacted with informal materials that will give indication of interest. Engage with sof, informational content to help them better identify what their actual problem is and ways to work towards a potential solution.',
                    'Has engaged in the buying process and is more concerned with results and big picture messaging than the functionality of product. Targeted messaging with upper level implications is key to continuing down the funnel.',
                    'Needs a reason to buy and we must message them according to where they are in the buying stage. This information should continue to be somewhat educational but pushing towards a decision for funnel.',
                    'Custom description'
                )
            ),
            array(
                'name' => 'VP Sales',
                'columns' => array(
                    'Similar to CMO, concerned with bigger picture messages that will be helpful to the overall performance of their team. Prefers content with simple, actionable messaging as well as hard facts, numbers, and graphs/charts.',
                    'Looking for specifici information to pass along to Director or Manager for actions. Has interacted with informal materials that will give indication of interest. Engage with soft, informational content to help them better indentify what their actual problem is and ways to work towards a potential solution.',
                    'Has engaged in the buying process and is more concerned with results and big picture messaging than the functionality of product. Targeted messaging with upper level implications is key to continuing down the funnel.',
                    'Needs a reason to buy and we must message them according to where they are in the buying stage. This information should continue to be somewhat educational but pushing towards a decision for funnel.',
                    'Custom description'
                )
            ),
            array(
                'name' => 'Sales Rep',
                'columns' => array(
                    'Focused on educating themselves with content that will help in their everyday work. Specific techniques and day-to-day actions or tools they can use are preferred over higher level, best practices messaging.',
                    'Will be looking for solutions to a specific problem. Materials to help them identify this solution are needed. More educational materials are needed with a slight hint at solutions offered.',
                    'Needs information to help them compare our solution to others. Facts driven, but still wants to be educated about both the overall problem, how their competitors are handling it and why we will provide the best in-calss results that are sustainable.',
                    'Stronger messaging driving to a purchase through targeted, solution driven content.',
                    'Custom description'
                )
            ),
            array(
                'name' => 'Product Manager',
                'columns' => array(
                    'Interested in understanding industry best practices as they pertain to the specific product and what their competition may be doing. Looking for messaging that will help give them an advantage over competitors along with things that will help them perform better daily.',
                    'Will be looking for solutions to a specific problem. Materials to help them identify this solution are needed. More educational materials are needed with a slight hint at solutions offered.',
                    'Needs information to help them compare our solution to others. Facts driven, but still wants to be educated about both the overall problem, how their competitors are handling it and why we will provide the best in-calss results that are sustainable.',
                    'Stronger messaging driving to a purchase through targeted, solution driven content.',
                    'Custom description'
                )
            )
        );
        $settings->save();

        $this->command->info('Saved content settings for Surge account');

        // Attach every connection to the Surge account

        $connections = Connection::all();
        foreach ($connections as $connection) {
            $connect = new AccountConnection;
            $connect->account_id = $account->id;
            $connect->connection_id = $connection->id;
            $connect->name = $connection->name;
            $connect->status = 1;
            $connect->settings = [];
            $connect->save();
        }

        $this->command->info('Saved content and seo connections to Surge account');

    }

}
