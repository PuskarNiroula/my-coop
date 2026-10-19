<?php

namespace App\Http\Controllers;

use App\Imports\MembersImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Member\Dto\MemberCreateDto;
use App\Http\Requests\StoreMemberRequest;
use Illuminate\View\View;
use Member\Service\MemberService;
use Throwable;

class MemberController extends Controller
{

    public function __construct(
        private readonly MemberService $memberService
    ){}

    public function index(): View{
        return view("member.index");
    }

    public function createMember(): View
    {
        return view("member.create");
    }

    public function storeMember(StoreMemberRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $dto = MemberCreateDto::fromArray($validated);

        try {
            $this->memberService->create($dto);

            return redirect()
                ->route('member.create')
                ->with('success', 'Member created successfully.');
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to create member. Please check the details and try again.');
        }
    }

    public function importMembers(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'excel_file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ]);

        try {
            Excel::import(new MembersImport($this->memberService), $validated['excel_file']);
            return redirect()->route('members.import')->with('success', 'Members imported successfully.');
        } catch (Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function import(): View
    {
        return view("member.import_member");
    }

}
