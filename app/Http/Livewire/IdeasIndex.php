<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasIndex extends Component
{
    use WithPagination, WithAuthRedirects;

    public $status;
    public $category;
    public $filter;
    public $search;

    protected $queryString = [
        'status',
        'category',
        'filter',
        'search',
    ];

    protected $listeners = ['queryStringUpdatedStatus'];

    public function mount()
    {
        $this->status = request()->status ?? 'All';
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        if ($this->filter === 'My Ideas') {
            if (auth()->guest()) {
                return $this->redirectToLogin();
            }
        }
    }

    public function queryStringUpdatedStatus($newStatus)
    {
        $this->resetPage();
        $this->status = $newStatus;
    }

    public function render()
    {
        return view('livewire.ideas-index', [
            /*'ideas' => Idea::query()
                ->select('ideas.*', DB::raw('COUNT(`votes`.`id`) AS votes_count'), DB::raw('COUNT(`comments`.`id`) AS comments_count'))
                ->when($this->status && $this->status !== 'All',
                    fn ($query) => $query->where('status_id', Status::pluck('id', 'name')->get($this->status))
                )
                ->when($this->category && $this->category !== 'All Categories',
                    fn ($query) => $query->where('category_id', Category::pluck('id', 'name')->get($this->category))
                )
                ->when($this->filter && $this->filter === 'Top Voted',
                    fn ($query) => $query->orderByDesc('votes_count')
                )
                ->when($this->filter && $this->filter === 'My Ideas',
                    fn ($query) => $query->where('ideas.user_id', auth()->id())
                )
                ->when($this->filter && $this->filter === 'Spam Ideas',
                    fn ($query) => $query->where('spam_reports', '>', 0)->orderByDesc('spam_reports')
                )
                ->when(strlen($this->search) >= 3,
                    fn ($query) => $query->where('title', 'like', '%'.$this->search.'%')
                )
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id', 'ideas.id')
                ])
                ->with('user:id,email', 'category:id,name', 'status:id,name')
                ->leftJoin('votes', 'ideas.id', 'votes.idea_id')
                ->leftJoin('comments', 'ideas.id', 'comments.idea_id')
                ->latest('id')
                ->groupBy('ideas.id')
                ->simplePaginate()
                ->withQueryString(),*/
            'ideas' => Idea::query()
                ->with('user:id,email', 'category:id,name', 'status:id,name')
                ->when($this->status && $this->status !== 'All',
                    fn ($query) => $query->where('status_id', Status::pluck('id', 'name')->get($this->status))
                )
                ->when($this->category && $this->category !== 'All Categories',
                    fn ($query) => $query->where('category_id', Category::pluck('id', 'name')->get($this->category))
                )
                ->when($this->filter && $this->filter === 'Top Voted',
                    fn ($query) => $query->orderByDesc('votes_count')
                )
                ->when($this->filter && $this->filter === 'My Ideas',
                    fn ($query) => $query->where('ideas.user_id', auth()->id())
                )
                ->when($this->filter && $this->filter === 'Spam Ideas',
                    fn ($query) => $query->where('spam_reports', '>', 0)->orderByDesc('spam_reports')
                )
                ->when($this->filter && $this->filter === 'Spam Comments',
                    fn ($query) => $query->whereHas('comments', fn ($query) => $query->where('spam_reports', '>', 0))
                )
                ->when(strlen($this->search) >= 3,
                    fn ($query) => $query->where('title', 'like', '%'.$this->search.'%')
                )
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id', 'ideas.id')
                ])
                ->withCount('votes')
                ->withCount('comments')
                ->latest('id')
                ->simplePaginate()
                ->withQueryString(),
            'categories' => Category::pluck('name'),
        ]);
    }
}
