<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('assets/logo_email.png')}}" class="logo" alt="RKG Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
