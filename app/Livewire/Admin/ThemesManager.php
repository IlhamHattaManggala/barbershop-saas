<?php

namespace App\Livewire\Admin;

use App\Models\SiteSetting;
use App\Models\Theme;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ThemesManager extends Component
{
    use WithFileUploads;

    public $search = '';

    public $typeFilter = 'all'; // 'all', 'free', 'premium'

    // Form Modal State
    public $showModal = false;

    public $editingThemeId = null;

    public $name = '';

    public $slug = '';

    public $type = 'free';

    public $price = 0;

    public $description = '';

    public $blade_view = 'themes.';

    public $is_active = true;

    public $new_thumbnail = null;

    public $current_thumbnail = '';

    public $theme_preview_enabled = true;

    public $successMessage = '';

    public $errorMessage = '';

    public function mount()
    {
        $this->theme_preview_enabled = (SiteSetting::get('theme_preview_enabled', '1') === '1');
    }

    public function togglePreviewSetting()
    {
        $current = SiteSetting::get('theme_preview_enabled', '1');
        $new = ($current === '1') ? '0' : '1';
        SiteSetting::set('theme_preview_enabled', $new);
        $this->theme_preview_enabled = ($new === '1');
        $statusText = ($new === '1') ? 'AKTIF (Publik Dapat Mengakses Preview)' : 'DINONAKTIFKAN (Khusus Superadmin/Development)';
        $this->successMessage = "Status Akses Pratinjau Tema (Preview Mode) berhasil diubah: {$statusText}.";
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:themes,slug,'.($this->editingThemeId ?: 'NULL'),
            'type' => 'required|in:free,premium',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'blade_view' => 'required|string|max:150',
            'is_active' => 'boolean',
            'new_thumbnail' => 'nullable|image|max:2048',
        ];
    }

    public function updatedName($value)
    {
        if (! $this->editingThemeId) {
            $this->slug = Str::slug($value);
            if (empty($this->blade_view) || $this->blade_view === 'themes.') {
                $this->blade_view = 'themes.'.$this->slug;
            }
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editTheme($id)
    {
        $theme = Theme::findOrFail($id);
        $this->editingThemeId = $theme->id;
        $this->name = $theme->name;
        $this->slug = $theme->slug;
        $this->type = $theme->type;
        $this->price = (float) $theme->price;
        $this->description = $theme->description;
        $this->blade_view = $theme->blade_view;
        $this->is_active = (bool) $theme->is_active;
        $this->current_thumbnail = $theme->thumbnail;
        $this->new_thumbnail = null;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingThemeId = null;
        $this->name = '';
        $this->slug = '';
        $this->type = 'free';
        $this->price = 0;
        $this->description = '';
        $this->blade_view = 'themes.';
        $this->is_active = true;
        $this->new_thumbnail = null;
        $this->current_thumbnail = '';
        $this->resetErrorBag();
    }

    public function saveTheme()
    {
        $validated = $this->validate();

        $thumbnailPath = $this->current_thumbnail;

        if ($this->new_thumbnail) {
            $filename = 'theme_'.time().'_'.Str::random(6).'.'.$this->new_thumbnail->getClientOriginalExtension();
            $this->new_thumbnail->storeAs('public/themes', $filename);
            $thumbnailPath = 'storage/themes/'.$filename;
        }

        $themePrice = ($this->type === 'free') ? 0 : ($this->price ?: 0);

        if ($this->editingThemeId) {
            $theme = Theme::findOrFail($this->editingThemeId);
            $theme->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
                'price' => $themePrice,
                'description' => $this->description,
                'blade_view' => $this->blade_view,
                'is_active' => $this->is_active,
                'thumbnail' => $thumbnailPath,
            ]);
            $this->successMessage = "Tema '{$theme->name}' berhasil diperbarui!";
        } else {
            $theme = Theme::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
                'price' => $themePrice,
                'description' => $this->description,
                'blade_view' => $this->blade_view,
                'is_active' => $this->is_active,
                'thumbnail' => $thumbnailPath,
            ]);
            $this->successMessage = "Tema baru '{$theme->name}' ({$theme->type}) berhasil ditambahkan!";
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update(['is_active' => ! $theme->is_active]);
        $statusText = $theme->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->successMessage = "Status tema '{$theme->name}' berhasil {$statusText}.";
    }

    public function deleteTheme($id)
    {
        $theme = Theme::findOrFail($id);
        $themeName = $theme->name;
        $theme->delete();
        $this->successMessage = "Tema '{$themeName}' berhasil dihapus.";
    }

    public function render()
    {
        $themes = Theme::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('slug', 'like', '%'.$this->search.'%'))
            ->when($this->typeFilter !== 'all', fn ($q) => $q->where('type', $this->typeFilter))
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.admin.themes-manager', [
            'themes' => $themes,
        ])->layout('layouts.app');
    }
}
