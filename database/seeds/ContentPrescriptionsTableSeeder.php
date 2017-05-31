<?php

use App\User;

class ContentPrescriptionsTableSeeder extends BaseSeeder {

    /**
     * Run the database seeds.
     * @return void
     */
    public function run ()
    {
        $this->disableForeignKeys();

        DB::table('content_prescriptions')->truncate();
        $this->insertB2CPrescriptions();
        $this->insertB2BPrescriptions();

        $this->enableForeignKeys();
    }

    public function insertB2CPrescriptions ()
    {
        DB::table('content_prescriptions')->insert([
            // Traffic from search engines
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 500, 'content_package' => '5 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 1000, 'content_package' => '10 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 2000, 'content_package' => '20 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 4000, 'content_package' => '44 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 6000, 'content_package' => '66 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 8000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages'],
            ['company_type' => 'B2C', 'goal' => 'traffic-from-search-engines', 'budget' => 16000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages + 2 Explainer Videos (1:30 min)'],

            // Lead Generation
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 500, 'content_package' => 'eBook or Product Guide (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 1000, 'content_package' => '2 eBooks or Product Guides (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 2000, 'content_package' => '4 eBooks or Product Guides (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 4000, 'content_package' => '8 eBooks or Product Guides (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 6000, 'content_package' => '12 eBooks/Product Guides (2K words) OR 6 eBooks/Product Guides (4K words)'],
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 8000, 'content_package' => '12 eBooks/Product Guides (2K words) OR 6 eBooks/Product Guides (4K words) + Explainer Video (1 Minute)'],
            ['company_type' => 'B2C', 'goal' => 'lead-generation', 'budget' => 16000, 'content_package' => '12 eBooks/Product Guides (2K words) OR 6 eBooks/Product Guides (4K words) + Explainer Video (1 Minute) + 20 Customer Testimonials'],

            // Converting Leads Into Customers
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 500, 'content_package' => '5 emails (400 words)'],
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 1000, 'content_package' => '10 emails (400 words)'],
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 2000, 'content_package' => '20 emails (400 words)'],
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 4000, 'content_package' => '20 emails (400 words) + Explainer Video (1 minute)'],
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 6000, 'content_package' => '20 emails (400 words) + 2 Explainer Videos (1 minute)'],
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 8000, 'content_package' => '20 emails (400 words) + 2 Explainer Videos (1 minute) + 2 Infographics'],
            ['company_type' => 'B2C', 'goal' => 'converting-leads-into-customers', 'budget' => 16000, 'content_package' => '20 emails (400 words) + 2 Explainer Videos (1 minute) + 2 Infographics + 40 Blogs'],

            // Branding
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 500, 'content_package' => 'eBook or Product Guide (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 1000, 'content_package' => '2 eBooks or Product Guides (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 2000, 'content_package' => '4 eBooks or Product Guides (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 4000, 'content_package' => '8 eBooks or Product Guides (2K words)'],
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 6000, 'content_package' => '12 eBooks/Product Guides (2K words) OR 6 eBooks/Product Guides (4K words)'],
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 8000, 'content_package' => '12 eBooks/Product Guides (2K words) OR 6 eBooks/Product Guides (4K words) + Explainer Video (1 Minute)'],
            ['company_type' => 'B2C', 'goal' => 'branding', 'budget' => 16000, 'content_package' => '12 eBooks/Product Guides (2K words) OR 6 eBooks/Product Guides (4K words) + Explainer Video (1 Minute) + 20 Customer Testimonials'],

            // Thought Leadership
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 500, 'content_package' => '5 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 1000, 'content_package' => '10 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 2000, 'content_package' => '20 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 4000, 'content_package' => '44 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 6000, 'content_package' => '66 blog posts (500 words)'],
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 8000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages'],
            ['company_type' => 'B2C', 'goal' => 'thought-leadership', 'budget' => 16000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages + 2 Explainer Videos (1:30 min)'],

            // Customer Retention/Loyalty
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 500, 'content_package' => '5 emails (400 words)'],
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 1000, 'content_package' => '10 emails (400 words)'],
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 2000, 'content_package' => '20 emails (400 words)'],
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 4000, 'content_package' => '20 emails (400 words) + Explainer Video (1 minute)'],
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 6000, 'content_package' => '20 emails (400 words) + 2 Explainer Videos (1 minute)'],
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 8000, 'content_package' => '20 emails (400 words) + 2 Explainer Videos (1 minute) + 2 Infographics'],
            ['company_type' => 'B2C', 'goal' => 'customer-loyalty', 'budget' => 16000, 'content_package' => '20 emails (400 words) + 2 Explainer Videos (1 minute) + 2 Infographics + 40 Blogs']
        ]);
    }

    public function insertB2BPrescriptions ()
    {
        DB::table('content_prescriptions')->insert([
            // Traffic from search engines
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 500, 'content_package' => '5 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 1000, 'content_package' => '10 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 2000, 'content_package' => '20 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 4000, 'content_package' => '44 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 6000, 'content_package' => '66 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 8000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages'],
            ['company_type' => 'B2B', 'goal' => 'traffic-from-search-engines', 'budget' => 16000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages + 2 Explainer Videos (1:30 min)'],

            // Lead Generation
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 500, 'content_package' => 'eBook (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 1000, 'content_package' => '2 eBooks (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 2000, 'content_package' => '4 eBooks (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 4000, 'content_package' => '8 eBooks (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 6000, 'content_package' => '12 eBooks (2K words) OR 6 eBooks (4K words)'],
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 8000, 'content_package' => '12 eBooks (2K words) OR 6 eBooks (4K words) + 8 case studies'],
            ['company_type' => 'B2B', 'goal' => 'lead-generation', 'budget' => 16000, 'content_package' => '12 eBooks (2K words) OR 6 eBooks (4K words) + 8 case studies + 2 Explainer Videos (1:30 min)'],

            // Converting Leads Into Customers
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 500, 'content_package' => '2 case studies (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 1000, 'content_package' => '4 case studies (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 2000, 'content_package' => '8 case studies (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 4000, 'content_package' => '16 case studies (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 6000, 'content_package' => '8 case studies (500 words) + Explainer Video (1:30 min)'],
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 8000, 'content_package' => '8 case studies (500 words) + Explainer Video (1:30 min) + infographic'],
            ['company_type' => 'B2B', 'goal' => 'converting-leads-into-customers', 'budget' => 16000, 'content_package' => '16 case studies (500 words) + 2 Explainer Videos (1:30 min) + 2 infographics + 20 blogs'],

            // Branding
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 500, 'content_package' => 'eBook (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 1000, 'content_package' => '2 eBooks (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 2000, 'content_package' => '4 eBooks (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 4000, 'content_package' => '8 eBooks (2K words)'],
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 6000, 'content_package' => '12 eBooks (2K words) OR 6 eBooks (4K words)'],
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 8000, 'content_package' => '12 eBooks (2K words) OR 6 eBooks (4K words) + 8 case studies'],
            ['company_type' => 'B2B', 'goal' => 'branding', 'budget' => 16000, 'content_package' => '12 eBooks (2K words) OR 6 eBooks (4K words) + 8 case studies + 2 Explainer Videos (1:30 min)'],

            // Thought Leadership
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 500, 'content_package' => '5 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 1000, 'content_package' => '10 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 2000, 'content_package' => '20 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 4000, 'content_package' => '44 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 6000, 'content_package' => '66 blog posts (500 words)'],
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 8000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages'],
            ['company_type' => 'B2B', 'goal' => 'thought-leadership', 'budget' => 16000, 'content_package' => '80 blog posts (500 words) + 11 Website Pages + 2 Explainer Videos (1:30 min)']
        ]);
    }
}
