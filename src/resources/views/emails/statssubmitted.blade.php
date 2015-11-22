Hi {{ $user }},<br/>
<br/>
Thank you for submitting stats for team {{ $centerName }}.

@if ($isLate)
Your stats are late. They were due on {{ $due }}.
@endif

We received them on {{ $time }} your local time. Please find the submitted sheet attached.<br/>
<br/>

@if (isset($sheet['errors']) && $sheet['errors'])
    Your sheet contained errors. Please review the errors below and correct them. If you have any questions please reach out to your regional statistician.<br/>
    <br/>
@endif

You are not complete yet. Your regional statistician will review your sheet and declare you complete by {{ $respondByTime }} your local time Saturday morning.<br/>
<br/>

@if ($reportUrl)
<a href="{{ $reportUrl }}">View your report online: {{ $centerName }} - {{ $reportingDate->format('M j, Y') }}</a><br/>
<br/>
@endif
@if ($comment)
    You provided the following comment:<br/>
    -----<br/>
    {{ $comment }}
    <br/>
    -----<br/>
@endif
<br/>
@if ($sheet)
    @include('import.results', ['sheet' => $sheet, 'includeUl' => true])
@endif
<br/>
Best,<br/>
Your Regional Statisticans<br/>
