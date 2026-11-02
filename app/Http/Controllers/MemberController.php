<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMemberRequest;
use App\Imports\MembersImport;
use App\Models\AccountType;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Member\Dto\MemberCreateDto;
use App\Http\Requests\StoreMemberRequest;
use Illuminate\View\View;
use Member\Dto\UpdateMemberDto;
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
        $accountTypes = AccountType::where('status','active')
            ->orderBy('account_type_id')
            ->pluck('name','account_type_id');
        return view("member.create",compact("accountTypes"));
    }

    public function storeMember(StoreMemberRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $dto = MemberCreateDto::fromArray($validated);

            $this->memberService->create($dto);

            return redirect()
                ->route('member.create')
                ->with('success', 'Member created successfully.');
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to create member. Please try again.');
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

    public function edit(int $memberId): View{
        $member = Member::findOrFail($memberId)->load('savingAccount');
        $accountTypes = AccountType::where('status','active')->pluck('name','account_type_id');
        return view("member.edit",compact(['member','accountTypes']));

    }

    public function update(UpdateMemberRequest $request, int $memberId){

        try{
            $validated = $request->validated();
            $validated['id']=$memberId;
            $dto = UpdateMemberDto::fromArray($validated);
            $this->memberService->update($dto);
            return redirect()->route('members.index')->with('success', 'Member updated successfully.');
        }catch (Throwable $e){
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

    }

}
