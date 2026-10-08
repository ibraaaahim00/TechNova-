<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['site_settings', 'home_sections', 'pages', 'services', 'project_categories', 'projects', 'team_members', 'blog_categories', 'posts', 'testimonials', 'navigation_items'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->json('translations')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['navigation_items', 'testimonials', 'posts', 'blog_categories', 'team_members', 'projects', 'project_categories', 'services', 'pages', 'home_sections', 'site_settings'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('translations');
            });
        }
    }
};
