<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BilingualContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->translateSettings([
            'location' => 'متاحون حول العالم',
            'seo_title' => 'TechNova — حلول برمجية',
            'seo_description' => 'منتجات رقمية مدروسة نصممها ونطورها للفرق الطموحة.',
            'footer_blurb' => 'نحوّل الأفكار الطموحة إلى منتجات رقمية متقنة.',
            'brand_statement' => 'فضول يقوده الإتقان.',
            'copyright_text' => 'صُنع بعناية.',
        ]);

        $this->translateBy(Page::class, 'slug', [
            'about' => ['title' => 'نبني منتجات رقمية لها هدف.', 'eyebrow' => 'عن الاستوديو', 'intro' => 'تجمع TechNova بين تصميم المنتجات وهندسة البرمجيات لحل تحديات حقيقية.', 'seo_title' => 'من نحن — TechNova'],
            'services' => ['title' => 'من الفكرة الأولى إلى المنتج المتكامل.', 'eyebrow' => 'خدماتنا', 'intro' => 'خبرات مرنة تساعدك على إنجاز أهم أعمالك الرقمية.', 'seo_title' => 'الخدمات — TechNova'],
            'projects' => ['title' => 'أفكار تتحول إلى أثر حقيقي.', 'eyebrow' => 'معرض الأعمال', 'intro' => 'نماذج من المنتجات الرقمية والأفكار التي صممناها.', 'seo_title' => 'أعمال مختارة — TechNova'],
            'journal' => ['title' => 'أفكار قيد التطوير.', 'eyebrow' => 'مدونة TechNova', 'intro' => 'رؤى حول المنتجات والهندسة وإتقان صناعة الأدوات المفيدة.', 'seo_title' => 'المدونة — TechNova'],
            'contact' => ['title' => 'كل بداية جيدة تبدأ بتحية.', 'eyebrow' => 'لنتحدث', 'intro' => 'أخبرنا بما تفكر فيه وسنقترح عليك الخطوات التالية بوضوح.', 'seo_title' => 'تواصل معنا — TechNova'],
        ]);

        $this->translateBy(HomeSection::class, 'key', [
            'hero' => ['eyebrow' => 'نصمم ما يأتي بعد ذلك', 'title' => "منتجات رقمية\nتصنع فرقًا في الغد.", 'body' => 'نصمم ونبني برمجيات مدروسة تساعد الفرق الطموحة على التقدم بثقة.', 'primary_label' => 'ابدأ مشروعًا', 'secondary_label' => 'استكشف أعمالنا'],
            'intro' => ['eyebrow' => 'فريق صغير. رؤية واسعة.', 'title' => 'يجب أن تجعل البرمجيات التعقيد أبسط.', 'body' => 'من أول تصور إلى الإطلاق، نجمع بين التفكير في المنتج ودقة الهندسة لنصنع تجارب رقمية يحب الناس استخدامها.'],
            'services_section' => ['eyebrow' => 'ماذا نقدم', 'title' => "تفكير واضح.\nتنفيذ متقن."],
            'projects_section' => ['eyebrow' => 'أعمال مختارة', 'title' => "أعمال تصنع\nفرقًا حقيقيًا."],
            'process' => ['eyebrow' => 'طريقة عملنا', 'title' => 'من فكرة جريئة إلى واقع جميل.', 'body' => 'تواصل واضح وقرارات مدروسة وتقدم ثابت منذ اليوم الأول.', 'primary_label' => 'تعرّف على الفريق'],
            'process_step_1' => ['title' => 'اكتشاف', 'body' => 'نبدأ بفهم الناس والمشكلة والفرصة.'],
            'process_step_2' => ['title' => 'تصميم', 'body' => 'نحدد ما يستحق البناء قبل أن نبدأ التنفيذ.'],
            'process_step_3' => ['title' => 'بناء', 'body' => 'نطور منتجًا موثوقًا خطوة بخطوة.'],
            'process_step_4' => ['title' => 'تطوير', 'body' => 'نبقى قريبين ونتعلم ونحسن ما يأتي بعد ذلك.'],
            'team_section' => ['eyebrow' => 'الفريق وراء كل تفصيلة', 'title' => "أشخاص رائعون.\nمنتجات أفضل."],
            'testimonials_section' => ['eyebrow' => 'كلمات من رحلتنا', 'title' => "نبني معًا.\nونترك أثرًا طيبًا."],
            'posts_section' => ['eyebrow' => 'ملاحظات من الميدان', 'title' => "أفكار تستحق\nالمشاركة."],
            'about' => ['eyebrow' => 'عن الاستوديو', 'title' => 'نبني منتجات رقمية لها هدف.', 'body' => 'تجمع TechNova بين تصميم المنتجات وهندسة البرمجيات لحل تحديات ذات معنى.'],
            'about_approach' => ['eyebrow' => 'منهجنا', 'title' => 'التقنية الجيدة تبدأ بفهم الناس.', 'body' => 'نعمل مع الفرق لفهم أهدافهم، ثم نصمم وننفذ برمجيات تقربهم منها. منهجنا تعاوني وعملي ويركز على نتائج تدوم.', 'primary_label' => 'تواصل مع فريقنا'],
            'cta' => ['eyebrow' => 'فصل جديد يبدأ هنا', 'title' => "لديك فكرة جيدة؟\nلنحوّلها إلى واقع.", 'primary_label' => 'أخبرنا عنها'],
        ]);

        $this->translateBy(Service::class, 'slug', [
            'product-design' => ['title' => 'تصميم المنتجات', 'summary' => 'تجارب واضحة ومدروسة تتمحور حول مستخدميها.', 'description' => 'نجمع بين أبحاث المستخدمين واستراتيجية المنتج وتصميم الواجهات لنصنع منتجات رقمية سهلة ومميزة.', 'features' => ['اكتشاف المنتج والاستراتيجية', 'تصميم تجربة المستخدم', 'أنظمة الواجهات والنماذج الأولية']],
            'custom-software' => ['title' => 'برمجيات مخصصة', 'summary' => 'منصات ويب موثوقة تناسب طريقة عمل مؤسستك.', 'description' => 'نبني منصات تشغيل وتطبيقات للعملاء ببرمجيات آمنة ومرنة تستجيب للاحتياجات الواقعية.', 'features' => ['تطبيقات ومنصات الويب', 'تصميم الواجهات البرمجية والتكاملات', 'تحديث الأنظمة والدعم المستمر']],
            'mobile-experiences' => ['title' => 'تجارب الهاتف المحمول', 'summary' => 'منتجات مفيدة تقرّب خدماتك من الناس.', 'description' => 'نصمم ونطور تطبيقات هاتف متقنة تعمل في الواقع وتنمو مع منتجك.', 'features' => ['استراتيجية منتجات الهاتف', 'تطبيقات أصلية ومتعددة المنصات', 'الإطلاق والتحسين المستمر']],
        ]);

        $this->translateBy(ProjectCategory::class, 'slug', ['product-concepts' => ['name' => 'أفكار منتجات']]);
        $this->translateBy(Project::class, 'slug', [
            'orbit-workspace' => ['title' => 'Orbit — مساحة لإدارة العمل', 'summary' => 'تصور يجمع سير عمل الفرق الموزعة في مساحة منظمة.', 'description' => 'يستكشف Orbit طريقة أكثر هدوءًا لتنظيم عمل الفرق ومتابعة العوائق والأهداف المشتركة. هذا مشروع تصوري مستقل وليس مشروعًا منفذًا لعميل.'],
            'northstar-learning' => ['title' => 'Northstar — منصة للتعلم', 'summary' => 'تصور مستقل لتجربة تعلم أكثر تركيزًا وإنسانية.', 'description' => 'يدرس Northstar دور مؤشرات التقدم الواضحة في تجربة التعلم عبر الإنترنت. إنه تصور ذاتي وليس مشروعًا منفذًا لعميل.'],
            'fieldnote-commerce' => ['title' => 'Fieldnote — تجارة مستدامة', 'summary' => 'متجر تصوري يبرز مصدر المنتجات وجودة صناعتها.', 'description' => 'Fieldnote تصور مستقل لتجارة إلكترونية تركز على قصة المنتج وتجربة تسوق هادئة، وليس عملًا لعميل حقيقي.'],
        ]);

        $this->translateBy(NavigationItem::class, 'label', [
            'Home' => ['label' => 'الرئيسية'], 'Services' => ['label' => 'الخدمات'], 'Work' => ['label' => 'أعمالنا'], 'About' => ['label' => 'من نحن'], 'Journal' => ['label' => 'المدونة'],
        ]);
    }

    /** @param array<string, string> $translations */
    private function translateSettings(array $translations): void
    {
        foreach ($translations as $key => $value) {
            $record = SiteSetting::query()->where('key', $key)->first();
            if ($record) {
                $this->fillMissingTranslation($record, 'value', $value);
            }
        }
    }

    /** @param class-string<Model> $modelClass
     * @param  array<string, array<string, mixed>>  $records
     */
    private function translateBy(string $modelClass, string $key, array $records): void
    {
        foreach ($records as $identity => $fields) {
            $record = $modelClass::query()->where($key, $identity)->first();
            if ($record) {
                foreach ($fields as $field => $value) {
                    $this->fillMissingTranslation($record, $field, $value);
                }
            }
        }
    }

    private function fillMissingTranslation(Model $record, string $field, mixed $value): void
    {
        $translations = $record->translations ?? [];
        if (filled($translations['ar'][$field] ?? null)) {
            return;
        }
        $translations['ar'][$field] = $value;
        $record->translations = $translations;
        $record->save();
    }
}
