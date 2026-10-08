<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'TechNova',
            'contact_email' => 'ebrahime131alaa@gmail.com',
            'location' => 'Available worldwide',
            'seo_title' => 'TechNova — Software Solutions',
            'seo_description' => 'Thoughtful software, designed and engineered for ambitious teams.',
            'footer_blurb' => 'We turn ambitious ideas into thoughtful digital products.',
            'brand_statement' => 'Curiosity meets craft.',
            'copyright_text' => 'Built with care.',
            'linkedin_url' => '',
            'github_url' => '',
            'logo_path' => '',
            'favicon_path' => '',
        ];
        foreach ($settings as $key => $value) {
            SiteSetting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $sections = [
            ['key' => 'hero', 'eyebrow' => 'Engineering the next', 'title' => "Digital products\nfor what comes next.", 'body' => 'We design and build thoughtful software that helps ambitious teams move forward with confidence.', 'primary_label' => 'Start a project', 'primary_url' => '/contact', 'secondary_label' => 'Explore our work', 'secondary_url' => '/projects', 'sort_order' => 1],
            ['key' => 'intro', 'eyebrow' => 'Small team. Big picture.', 'title' => 'Software should make the complex feel simple.', 'body' => 'From the first sketch to the final release, we bring product thinking and engineering craft together to create digital experiences people love to use.', 'sort_order' => 2],
            ['key' => 'services_section', 'eyebrow' => 'What we do', 'title' => "Good thinking.\nGreat building.", 'sort_order' => 3],
            ['key' => 'projects_section', 'eyebrow' => 'Selected work', 'title' => "Built to make\nthings better.", 'sort_order' => 4],
            ['key' => 'process', 'eyebrow' => 'How we work', 'title' => 'From bold idea to beautiful reality.', 'body' => 'Clear communication, thoughtful decisions, and steady progress from day one.', 'primary_label' => 'Meet your team', 'primary_url' => '/about', 'sort_order' => 5],
            ['key' => 'process_step_1', 'eyebrow' => '01', 'title' => 'Discover', 'body' => 'We start with the people, the problem, and the opportunity.', 'sort_order' => 6],
            ['key' => 'process_step_2', 'eyebrow' => '02', 'title' => 'Design', 'body' => 'We make the right things clear before we make them real.', 'sort_order' => 7],
            ['key' => 'process_step_3', 'eyebrow' => '03', 'title' => 'Build', 'body' => 'We craft a reliable product, release by release.', 'sort_order' => 8],
            ['key' => 'process_step_4', 'eyebrow' => '04', 'title' => 'Grow', 'body' => 'We stay close, learn, and improve what comes next.', 'sort_order' => 9],
            ['key' => 'team_section', 'eyebrow' => 'The people behind the pixels', 'title' => "Good people.\nBetter products.", 'sort_order' => 10],
            ['key' => 'testimonials_section', 'eyebrow' => 'Words from the journey', 'title' => "Built together.\nRemembered fondly.", 'sort_order' => 11],
            ['key' => 'posts_section', 'eyebrow' => 'Field notes', 'title' => "Thoughts worth\nsharing.", 'sort_order' => 12],
            ['key' => 'about', 'eyebrow' => 'About the studio', 'title' => 'We build with purpose.', 'body' => 'TechNova brings product design and software engineering together to solve meaningful problems.', 'sort_order' => 13],
            ['key' => 'about_approach', 'eyebrow' => 'Our approach', 'title' => 'Good technology starts with understanding people.', 'body' => 'We work alongside teams to understand where they are going, then shape and deliver software that gets them there. Our approach is collaborative, pragmatic, and built around lasting outcomes.', 'sort_order' => 9],
            ['key' => 'cta', 'eyebrow' => 'Your next chapter starts here', 'title' => "Have a good idea?\nLet's make it real.", 'primary_label' => 'Tell us about it', 'primary_url' => '/contact', 'sort_order' => 14],
        ];
        foreach ($sections as $section) {
            HomeSection::query()->firstOrCreate(['key' => $section['key']], $section);
        }

        $pages = [
            ['title' => 'We build with purpose.', 'slug' => 'about', 'eyebrow' => 'About the studio', 'intro' => 'TechNova brings product design and software engineering together to solve meaningful problems.', 'seo_title' => 'About — TechNova'],
            ['title' => 'From first thought to finished product.', 'slug' => 'services', 'eyebrow' => 'Capabilities', 'intro' => 'Flexible expertise to take your most important digital work forward.', 'seo_title' => 'Services — TechNova'],
            ['title' => 'Ideas, meet real-world impact.', 'slug' => 'projects', 'eyebrow' => 'Portfolio', 'intro' => 'A selection of digital products and concepts brought to life.', 'seo_title' => 'Selected work — TechNova'],
            ['title' => 'Ideas in progress.', 'slug' => 'journal', 'eyebrow' => 'Field notes', 'intro' => 'Perspectives on product, engineering, and the craft of making useful things.', 'seo_title' => 'Journal — TechNova'],
            ['title' => 'Good things start with hello.', 'slug' => 'contact', 'eyebrow' => 'Let’s talk', 'intro' => 'Tell us what you’re thinking about. We’ll get back to you with thoughtful next steps.', 'seo_title' => 'Contact — TechNova'],
        ];
        foreach ($pages as $page) {
            Page::query()->firstOrCreate(['slug' => $page['slug']], $page + ['is_published' => true]);
        }

        foreach ([['Home', '/'], ['Services', '/services'], ['Work', '/projects'], ['About', '/about'], ['Journal', '/journal']] as $index => [$label, $url]) {
            NavigationItem::query()->firstOrCreate(['label' => $label], ['url' => $url, 'sort_order' => $index + 1]);
        }

        $services = [
            ['title' => 'Product design', 'slug' => 'product-design', 'icon' => '✳', 'summary' => 'Clear, considered experiences built around the people who use them.', 'description' => 'We bring research, product strategy, and interface design together to make digital products feel intuitive and distinctive.', 'features' => ['Product discovery and strategy', 'User experience design', 'Interface systems and prototypes']],
            ['title' => 'Custom software', 'slug' => 'custom-software', 'icon' => '⌘', 'summary' => 'Reliable web platforms designed around how your business works.', 'description' => 'From operational platforms to customer-facing applications, we build secure and adaptable software around real needs.', 'features' => ['Web applications and platforms', 'API design and integrations', 'Modernization and ongoing support']],
            ['title' => 'Mobile experiences', 'slug' => 'mobile-experiences', 'icon' => '◈', 'summary' => 'Useful mobile products that bring your service closer to people.', 'description' => 'We design and engineer polished mobile applications that work well in the real world and grow with your product.', 'features' => ['Mobile product strategy', 'Native and cross-platform apps', 'Release and iteration']],
        ];
        foreach ($services as $index => $service) {
            Service::query()->firstOrCreate(['slug' => $service['slug']], $service + ['is_published' => true, 'is_featured' => true, 'sort_order' => $index + 1]);
        }

        $category = ProjectCategory::query()->firstOrCreate(['slug' => 'product-concepts'], ['name' => 'Product concepts']);
        $technologyNames = ['Laravel', 'PHP', 'JavaScript', 'MySQL', 'Figma'];
        $technologies = collect($technologyNames)->mapWithKeys(fn (string $name) => [$name => Technology::query()->firstOrCreate(['slug' => str($name)->slug()], ['name' => $name])]);

        $projects = [
            ['title' => 'Orbit — operations workspace', 'slug' => 'orbit-workspace', 'summary' => 'A concept for bringing distributed team workflows into one considered workspace.', 'description' => 'Orbit explores a calmer way for teams to coordinate recurring work, spot blockers, and keep shared goals visible. This independent portfolio concept is not represented as a commissioned client project.'],
            ['title' => 'Northstar — learning platform', 'slug' => 'northstar-learning', 'summary' => 'An independent concept for a more focused, human learning experience.', 'description' => 'Northstar is a self-initiated product concept examining the role of thoughtful progress cues in online learning. It is a portfolio exploration, not a commissioned client project.'],
            ['title' => 'Fieldnote — sustainable commerce', 'slug' => 'fieldnote-commerce', 'summary' => 'A concept store experience that puts provenance and craft in focus.', 'description' => 'Fieldnote is an independent ecommerce concept built around clear product stories and an unhurried shopping experience. It is not represented as work for a real client.'],
        ];
        foreach ($projects as $index => $project) {
            $record = Project::query()->firstOrCreate(['slug' => $project['slug']], $project + ['project_category_id' => $category->id, 'is_concept' => true, 'is_published' => true, 'is_featured' => true, 'sort_order' => $index + 1]);
            $record->technologies()->syncWithoutDetaching($technologies->slice($index, 3)->pluck('id'));
        }

        $this->call(BilingualContentSeeder::class);
    }
}
