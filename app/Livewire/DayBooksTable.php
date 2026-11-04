<?php

namespace App\Livewire;

use App\Models\DayBook;
use DayBook\Enum\DayBookStatusEnum;
use Exception;
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
    public function confirmClose(int $id)
    {
        $dayBook = DayBook::findOrFail($id);
        $dayBook->status = DayBookStatusEnum::CLOSED->value;
        $dayBook->save();
        return view('components.day-books-table')->with('success','day book closed');
    }


    /**
     * @throws Exception
     */
    public function reopenDayBook($id): void
    {
        $latestDayBook = DayBook::latest('day_book_id')->first();

        if (!$latestDayBook || $latestDayBook->day_book_id != $id) {
            $this->dispatch(
                'day-book-updated',
                title: 'Day Book Reopening Failed',
                message: 'Only the latest day book can be reopened.',
                icon: 'error'
            );

            return;
        }

        if ($latestDayBook->status !== 'closed') {
            $this->dispatch(
                'day-book-updated',
                title: 'Already Open',
                message: 'This day book is already open.',
                icon: 'info'
            );

            return;
        }

        $latestDayBook->update([
            'status' => 'open',
        ]);

        $this->dispatch(
            'day-book-updated',
            title: 'Day Book Reopened',
            message: 'The day book has been reopened successfully.',
            icon: 'success'
        );

        $this->resetPage();
    }
}
