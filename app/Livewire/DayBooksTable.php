<?php

namespace App\Livewire;

use App\Models\DayBook;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DayBooksTable extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function closeDayBook(int $dayBookId): void
    {
        DB::transaction(function () use ($dayBookId) {
            $dayBook = DayBook::where('day_book_id', $dayBookId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($dayBook->status !== 'open') {
                return;
            }

            $dayBook->update([
                'status' => 'closed',
            ]);
        });

        $this->dispatch('day-book-updated');
    }

    public function render()
    {
        $dayBooks = DayBook::query()
            ->when(
                trim($this->search) !== '',
                fn ($query) => $query->where(
                    'date_nepali',
                    'like',
                    '%' . trim($this->search) . '%'
                )
            )
            ->orderByDesc('date_english')
            ->paginate(10);

        return view('components.day-books-table', [
            'dayBooks' => $dayBooks,
        ]);
    }
}
