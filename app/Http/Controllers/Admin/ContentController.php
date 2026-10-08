<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\ContactMessage;
use App\Models\HomeSection;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Technology;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    private const TRANSLATABLE_FIELDS = [
        'services' => ['title', 'summary', 'description', 'features', 'seo_title', 'seo_description'],
        'projects' => ['title', 'summary', 'description', 'client_name', 'seo_title', 'seo_description'],
        'posts' => ['title', 'excerpt', 'body', 'seo_title', 'seo_description'],
        'team' => ['name', 'role', 'bio', 'skills'],
        'testimonials' => ['person_name', 'person_role', 'company', 'quote'],
        'sections' => ['eyebrow', 'title', 'body', 'primary_label', 'secondary_label'],
        'pages' => ['title', 'eyebrow', 'intro', 'seo_title', 'seo_description'],
        'settings' => ['value'],
        'navigation' => ['label'],
        'project-categories' => ['name'],
        'blog-categories' => ['name'],
    ];

    private const RESOURCES = [
        'services' => ['label' => 'Services', 'model' => Service::class, 'fields' => ['title' => 'text', 'slug' => 'text', 'icon' => 'text', 'summary' => 'textarea', 'description' => 'richtext', 'features' => 'list', 'image_path' => 'image', 'seo_title' => 'text', 'seo_description' => 'textarea', 'is_published' => 'boolean', 'is_featured' => 'boolean', 'sort_order' => 'number']],
        'projects' => ['label' => 'Projects', 'model' => Project::class, 'fields' => ['title' => 'text', 'slug' => 'text', 'project_category_id' => 'project_category', 'technologies' => 'technology_list', 'summary' => 'textarea', 'description' => 'richtext', 'client_name' => 'text', 'completed_at' => 'date', 'project_url' => 'url', 'github_url' => 'url', 'image_path' => 'image', 'seo_title' => 'text', 'seo_description' => 'textarea', 'is_concept' => 'boolean', 'is_published' => 'boolean', 'is_featured' => 'boolean', 'sort_order' => 'number']],
        'posts' => ['label' => 'Journal', 'model' => Post::class, 'fields' => ['title' => 'text', 'slug' => 'text', 'blog_category_id' => 'blog_category', 'excerpt' => 'textarea', 'body' => 'richtext', 'cover_path' => 'image', 'seo_title' => 'text', 'seo_description' => 'textarea', 'published_at' => 'datetime', 'is_published' => 'boolean']],
        'team' => ['label' => 'Team', 'model' => TeamMember::class, 'fields' => ['name' => 'text', 'role' => 'text', 'bio' => 'textarea', 'photo_path' => 'image', 'skills' => 'list', 'social_links' => 'links', 'is_active' => 'boolean', 'sort_order' => 'number']],
        'testimonials' => ['label' => 'Testimonials', 'model' => Testimonial::class, 'fields' => ['person_name' => 'text', 'person_role' => 'text', 'company' => 'text', 'quote' => 'textarea', 'photo_path' => 'image', 'is_approved' => 'boolean', 'sort_order' => 'number']],
        'sections' => ['label' => 'Homepage sections', 'model' => HomeSection::class, 'fields' => ['key' => 'text', 'eyebrow' => 'text', 'title' => 'text', 'body' => 'textarea', 'image_path' => 'image', 'primary_label' => 'text', 'primary_url' => 'link', 'secondary_label' => 'text', 'secondary_url' => 'link', 'is_visible' => 'boolean', 'sort_order' => 'number']],
        'pages' => ['label' => 'Pages', 'model' => Page::class, 'fields' => ['title' => 'text', 'slug' => 'text', 'eyebrow' => 'text', 'intro' => 'textarea', 'image_path' => 'image', 'seo_title' => 'text', 'seo_description' => 'textarea', 'is_published' => 'boolean']],
        'settings' => ['label' => 'Site settings', 'model' => SiteSetting::class, 'fields' => ['key' => 'text', 'value' => 'setting_value']],
        'navigation' => ['label' => 'Navigation', 'model' => NavigationItem::class, 'fields' => ['label' => 'text', 'url' => 'text', 'is_visible' => 'boolean', 'sort_order' => 'number']],
        'project-categories' => ['label' => 'Project categories', 'model' => ProjectCategory::class, 'fields' => ['name' => 'text', 'slug' => 'text']],
        'technologies' => ['label' => 'Technologies', 'model' => Technology::class, 'fields' => ['name' => 'text', 'slug' => 'text']],
        'blog-categories' => ['label' => 'Blog categories', 'model' => BlogCategory::class, 'fields' => ['name' => 'text', 'slug' => 'text']],
        'messages' => ['label' => 'Contact inbox', 'model' => ContactMessage::class, 'fields' => []],
    ];

    public function index(string $type): View
    {
        $config = $this->config($type);
        $records = $config['model']::query()->when($type === 'messages', fn ($query) => $query->whereNull('archived_at'))->latest()->paginate(20);

        return view('admin.content.index', compact('type', 'config', 'records'));
    }

    public function create(string $type): View
    {
        abort_if($type === 'messages', 404);
        $config = $this->config($type);

        return view('admin.content.form', ['type' => $type, 'config' => $config, 'record' => null]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        abort_if($type === 'messages', 404);
        $config = $this->config($type);
        $data = $this->validatedData($request, $type, $config, null);
        $technologyIds = $data['technologies'] ?? [];
        unset($data['technologies']);
        $record = $config['model']::query()->create($data);
        if ($type === 'projects') {
            $record->technologies()->sync($technologyIds);
        }

        return redirect()->route('admin.content.index', $type)->with('status', __('Created successfully.'));
    }

    public function edit(string $type, int $id): View
    {
        $config = $this->config($type);
        $record = $config['model']::query()->findOrFail($id);

        return view('admin.content.form', compact('type', 'config', 'record'));
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $config = $this->config($type);
        $record = $config['model']::query()->findOrFail($id);

        if ($type === 'messages') {
            $action = $request->validate(['action' => ['required', Rule::in(['read', 'unread', 'archive'])]])['action'];
            $record->forceFill(['read_at' => $action === 'read' ? now() : ($action === 'unread' ? null : $record->read_at), 'archived_at' => $action === 'archive' ? now() : $record->archived_at])->save();

            return back()->with('status', __('Message status updated.'));
        }

        $data = $this->validatedData($request, $type, $config, $record);
        $technologyIds = $data['technologies'] ?? [];
        unset($data['technologies']);
        $record->update($data);
        if ($type === 'projects') {
            $record->technologies()->sync($technologyIds);
        }

        return redirect()->route('admin.content.index', $type)->with('status', __('Changes saved successfully.'));
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $config = $this->config($type);
        $record = $config['model']::query()->findOrFail($id);

        foreach (['image_path', 'cover_path', 'photo_path'] as $imageField) {
            if ($record->{$imageField}) {
                Storage::disk('public')->delete($record->{$imageField});
            }
        }

        $record->delete();

        return redirect()->route('admin.content.index', $type)->with('status', __('Deleted successfully.'));
    }

    /** @return array{label: string, model: class-string<Model>, fields: array<string, string>} */
    private function config(string $type): array
    {
        abort_unless(isset(self::RESOURCES[$type]), 404);

        $config = self::RESOURCES[$type];
        $config['translated_fields'] = self::TRANSLATABLE_FIELDS[$type] ?? [];

        return $config;
    }

    /** @param array{label: string, model: class-string<Model>, fields: array<string, string>} $config
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, string $type, array $config, ?Model $record): array
    {
        $rules = [];
        $translatable = self::TRANSLATABLE_FIELDS[$type] ?? [];
        if ($type === 'settings' && in_array($request->input('key'), ['logo_path', 'favicon_path'], true)) {
            $translatable = [];
        }
        foreach ($translatable as $field) {
            if ($request->exists($field) && ! $request->exists("translations.en.$field")) {
                $request->merge(['translations' => array_replace_recursive($request->input('translations', []), ['en' => [$field => $request->input($field)]])]);
            }
        }

        foreach ($config['fields'] as $field => $kind) {
            $fieldRules = match ($kind) {
                'text' => ['nullable', 'string', 'max:255'],
                'textarea', 'richtext' => ['nullable', 'string', 'max:20000'],
                'number' => ['nullable', 'integer', 'min:0', 'max:999999'],
                'boolean' => ['sometimes', 'boolean'],
                'url' => ['nullable', 'url:http,https', 'max:2048'],
                'link' => ['nullable', 'string', 'max:2048', function (string $attribute, mixed $value, \Closure $fail): void {
                    if (blank($value) || str_starts_with($value, '#')) {
                        return;
                    }
                    if (str_starts_with($value, '/') && ! str_starts_with($value, '//') && ! str_contains($value, '\\')) {
                        return;
                    }
                    $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
                    if (! filter_var($value, FILTER_VALIDATE_URL) || ! in_array($scheme, ['http', 'https'], true)) {
                        $fail('Enter a local path or an HTTP/HTTPS URL.');
                    }
                }],
                'date' => ['nullable', 'date'],
                'datetime' => ['nullable', 'date'],
                'image' => ['nullable', 'image', 'max:5120'],
                'list' => ['nullable', 'string', 'max:5000'],
                'links' => ['nullable', 'string', 'max:5000'],
                'setting_value' => in_array($request->input('key'), ['logo_path', 'favicon_path'], true) ? ['nullable', 'image', 'max:5120'] : ['nullable', 'string', 'max:5000'],
                'project_category', 'blog_category' => ['nullable', 'integer', 'exists:'.($kind === 'project_category' ? 'project_categories' : 'blog_categories').',id'],
                'technology_list' => ['nullable', 'array'],
                default => ['nullable'],
            };
            if (in_array($field, $translatable, true)) {
                foreach (['en', 'ar'] as $locale) {
                    $rules["translations.$locale.$field"] = $fieldRules;
                }
            } else {
                $rules[$field] = $fieldRules;
            }
        }
        foreach (['slug'] as $slugField) {
            if (array_key_exists($slugField, $config['fields'])) {
                $table = (new $config['model'])->getTable();
                $rules[$slugField] = ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique($table, $slugField)->ignore($record?->getKey())];
            }
        }
        if (in_array($type, ['sections', 'settings'], true)) {
            $rules['key'] = ['required', 'string', 'max:100', Rule::unique((new $config['model'])->getTable(), 'key')->ignore($record?->getKey())];
        }
        if (in_array($type, ['project-categories', 'technologies', 'blog-categories'], true) && ! in_array('name', $translatable, true)) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }
        $requiredTranslations = match ($type) {
            'services', 'projects', 'posts', 'pages' => ['title'],
            'team' => ['name', 'role'],
            'testimonials' => ['person_name', 'quote'],
            'project-categories', 'blog-categories' => ['name'],
            default => [],
        };
        foreach ($requiredTranslations as $field) {
            foreach (['en', 'ar'] as $locale) {
                $otherLocale = $locale === 'en' ? 'ar' : 'en';
                $rules["translations.$locale.$field"] = ['nullable', 'string', 'max:'.($field === 'quote' ? 5000 : 255)];
                if ($locale === 'en') {
                    $rules["translations.$locale.$field"][] = "required_without:translations.$otherLocale.$field";
                }
            }
        }
        if ($type === 'navigation') {
            $rules['url'] = ['required', 'string', 'max:2048', function (string $attribute, mixed $value, \Closure $fail): void {
                $isLocalPath = str_starts_with($value, '/') && ! str_starts_with($value, '//') && ! str_contains($value, '\\');
                $isFragment = str_starts_with($value, '#');
                $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
                $isExternalUrl = filter_var($value, FILTER_VALIDATE_URL) && in_array($scheme, ['http', 'https'], true);

                if (! $isLocalPath && ! $isFragment && ! $isExternalUrl) {
                    $fail('Enter a local path, fragment, or HTTP/HTTPS URL.');
                }
            }];
        }

        $data = $request->validate($rules);
        $translationData = $data['translations'] ?? [];
        unset($data['translations']);
        $translations = $record?->translations ?? [];
        foreach (['en', 'ar'] as $locale) {
            foreach ($translatable as $field) {
                if (array_key_exists($field, $translationData[$locale] ?? [])) {
                    $value = $translationData[$locale][$field];
                    if (in_array($field, ['features', 'skills'], true)) {
                        $value = collect(preg_split('/\r\n|\r|\n/', (string) $value))->map(fn ($item) => trim($item))->filter()->values()->all();
                    }
                    $translations[$locale][$field] = $value;
                }
            }
        }
        if ($translatable !== []) {
            $data['translations'] = $translations;
            foreach ($requiredTranslations as $field) {
                if (! array_key_exists($field, $data)) {
                    $data[$field] = $translations['en'][$field] ?? $translations['ar'][$field] ?? null;
                }
            }
            if (in_array('value', $translatable, true) && $type === 'settings') {
                $data['value'] = $translations['en']['value'] ?? $translations['ar']['value'] ?? ($record?->getRawOriginal('value'));
            }
        }
        if ($type === 'projects') {
            $data = array_merge($data, $request->validate(['technologies' => ['nullable', 'array'], 'technologies.*' => ['integer', 'exists:technologies,id']]));
        }
        foreach ($config['fields'] as $field => $kind) {
            if ($kind === 'boolean') {
                $data[$field] = $request->boolean($field);
            } elseif ($kind === 'list') {
                $data[$field] = collect(preg_split('/\r\n|\r|\n/', (string) ($data[$field] ?? '')))->map(fn ($item) => trim($item))->filter()->values()->all();
            } elseif ($kind === 'links') {
                $data[$field] = collect(preg_split('/\r\n|\r|\n/', (string) ($data[$field] ?? '')))->mapWithKeys(function ($line): array {
                    [$label, $url] = array_pad(explode('|', $line, 2), 2, null);

                    return [trim((string) $label) => trim((string) $url)];
                })->filter(fn ($url, $label) => $label !== '' && filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true))->all();
            } elseif ($kind === 'image' && ($request->file($field) instanceof UploadedFile)) {
                $path = $request->file($field)->store('cms', 'public');
                if ($record?->{$field}) {
                    Storage::disk('public')->delete($record->{$field});
                }
                $data[$field] = $path;
            } elseif ($kind === 'image') {
                unset($data[$field]);
            } elseif ($kind === 'setting_value' && in_array($request->input('key'), ['logo_path', 'favicon_path'], true)) {
                if ($request->file($field) instanceof UploadedFile) {
                    $path = $request->file($field)->store('cms/branding', 'public');
                    if ($record?->value) {
                        Storage::disk('public')->delete($record->value);
                    }
                    $data[$field] = $path;
                } else {
                    unset($data[$field]);
                }
            }
        }
        if (array_key_exists('slug', $config['fields']) && blank($data['slug'] ?? null)) {
            $data['slug'] = str($data['title'] ?? $data['name'] ?? 'item')->slug().'-'.str()->random(5);
        }
        if ($type === 'posts' && ($data['is_published'] ?? false) && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
