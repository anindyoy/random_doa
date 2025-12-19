<?php

use App\Models\Doa;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use function Pest\Livewire\livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Doas\Pages\EditDoa;
use App\Filament\Resources\Doas\Pages\CreateDoa;

beforeEach(function () {
    Storage::fake('public');
    $this->adminUser = User::factory()->create(['is_admin' => true]);
    $this->regularUser = User::factory()->create(['is_admin' => false]);
});

describe('DoaForm Structure', function () {
    test('form exists', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormExists();
    });

    test('has all required fields', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormFieldExists('user_id')
            ->assertFormFieldExists('judul')
            ->assertFormFieldExists('keterangan')
            ->assertFormFieldExists('gambar')
            ->assertFormFieldExists('sumber_desain')
            ->assertFormFieldExists('visibility')
            ->assertFormFieldExists('untuk_pribadi')
            ->assertFormFieldExists('tags')
            ->assertFormFieldExists('riwayat');
    });
});

describe('Field Visibility', function () {
    test('user_id field is visible for admin', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormFieldVisible('user_id');
    });

    test('user_id field is hidden for regular user', function () {
        $this->actingAs($this->regularUser);

        livewire(CreateDoa::class)
            ->assertFormFieldHidden('user_id');
    });
});

describe('Field Configuration', function () {
    test('user_id is a select with user relationship', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormFieldExists('user_id', function (Select $field): bool {
                return $field->getRelationshipName() === 'user';
            });
    });

    test('judul field is required', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormFieldExists('judul', function (TextInput $field): bool {
                return $field->isRequired();
            });
    });

    test('gambar accepts only images', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormFieldExists('gambar', function (FileUpload $field): bool {
                return $field->getAcceptedFileTypes() !== null;
            });
    });

    test('tags field is multiple select with relationship', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertFormFieldExists('tags', function (Select $field): bool {
                return $field->isMultiple() &&
                    $field->isSearchable() &&
                    $field->isPreloaded();
            });
    });
});

describe('Form Validation', function () {
    test('validates required judul field', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->fillForm([
                'judul' => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['judul' => 'required']);
    });

    test('validates required user_id for admin', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->fillForm([
                'user_id' => null,
                'judul' => 'Test Doa',
            ])
            ->call('create')
            ->assertHasFormErrors(['user_id' => 'required']);
    });

    test('passes validation with valid data', function () {
        $this->actingAs($this->adminUser);
        $user = User::factory()->create();

        livewire(CreateDoa::class)
            ->fillForm([
                'user_id' => $user->id,
                'judul' => 'Test Doa Title',
                'keterangan' => 'Test description',
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    });
});

describe('Form Fill and State', function () {
    test('can fill form with basic data', function () {
        $this->actingAs($this->adminUser);
        $user = User::factory()->create();

        livewire(CreateDoa::class)
            ->fillForm([
                'user_id' => $user->id,
                'judul' => 'Test Doa',
                'keterangan' => 'Test keterangan',
                'sumber_desain' => 'Test source',
                'riwayat' => 'Test riwayat',
            ])
            ->assertSchemaStateSet([
                'user_id' => $user->id,
                'judul' => 'Test Doa',
                'keterangan' => 'Test keterangan',
                'sumber_desain' => 'Test source',
                'riwayat' => 'Test riwayat',
            ]);
    });

    test('can fill toggle fields', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->fillForm([
                'judul' => 'Test',
                'visibility' => true,
                'untuk_pribadi' => false,
            ])
            ->assertSchemaStateSet([
                'visibility' => true,
                'untuk_pribadi' => false,
            ]);
    });

    test('can fill tags field with multiple tags', function () {
        $this->actingAs($this->adminUser);
        $tags = Tag::factory()->count(3)->create();

        livewire(CreateDoa::class)
            ->fillForm([
                'judul' => 'Test',
                'tags' => $tags->pluck('id')->toArray(),
            ])
            ->assertSchemaStateSet([
                'tags' => $tags->pluck('id')->toArray(),
            ]);
    });
});

describe('Edit Form', function () {
    test('edit form loads existing data', function () {
        $this->actingAs($this->adminUser);
        $doa = Doa::factory()->create([
            'judul' => 'Existing Doa',
            'keterangan' => 'Existing keterangan',
            'visibility' => true,
        ]);

        livewire(EditDoa::class, ['record' => $doa->getRouteKey()])
            ->assertFormSet([
                'judul' => 'Existing Doa',
                'keterangan' => 'Existing keterangan',
                'visibility' => true,
            ]);
    });

    test('can update existing doa', function () {
        $this->actingAs($this->adminUser);
        $doa = Doa::factory()->create([
            'judul' => 'Old Title',
        ]);

        livewire(EditDoa::class, ['record' => $doa->getRouteKey()])
            ->fillForm([
                'judul' => 'New Title',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($doa->fresh()->judul)->toBe('New Title');
    });
});

describe('File Upload', function () {
    test('can upload image file', function () {
        $this->actingAs($this->adminUser);
        $file = UploadedFile::fake()->image('test-doa.jpg');

        livewire(CreateDoa::class)
            ->fillForm([
                'judul' => 'Test with Image',
                'gambar' => $file,
            ])
            ->assertSchemaStateSet(function (array $state): array {
                expect($state['gambar'])->not->toBeNull();
                return [];
            });
    });
});

describe('Default Values', function () {
    test('keterangan has null default value', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertSchemaStateSet([
                'keterangan' => null,
            ]);
    });

    test('sumber_desain has null default value', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertSchemaStateSet([
                'sumber_desain' => null,
            ]);
    });

    test('riwayat has null default value', function () {
        $this->actingAs($this->adminUser);

        livewire(CreateDoa::class)
            ->assertSchemaStateSet([
                'riwayat' => null,
            ]);
    });
});

describe('Tags Creation', function () {
    test('can select multiple existing tags', function () {
        $this->actingAs($this->adminUser);
        $tags = Tag::factory()->count(3)->create();

        livewire(CreateDoa::class)
            ->fillForm([
                'judul' => 'Test',
                'tags' => $tags->pluck('id')->toArray(),
            ])
            ->assertSchemaStateSet(function (array $state) use ($tags): array {
                expect($state['tags'])
                    ->toBeArray()
                    ->toHaveCount(3)
                    ->toMatchArray($tags->pluck('id')->toArray());
                return [];
            });
    });

    test('can create new tag through tags field', function () {
        $this->actingAs($this->adminUser);
        $initialTagCount = Tag::count();

        livewire(CreateDoa::class)
            ->callFormComponentAction('tags', 'createOption', data: [
                'nama' => 'New Tag',
                'deskripsi' => 'New tag description',
            ])
            ->assertHasNoFormErrors();

        $finalTagCount = Tag::count();
        expect($finalTagCount)->toBe($initialTagCount + 1);
    })->only();
});
