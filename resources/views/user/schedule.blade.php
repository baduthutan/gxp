@extends('layouts.frontend.app')

@section('page_content')
    <section id="home" class="home d-flex align-items-center" data-scroll-index="0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 my-5">
                    <h1 class="mt-5">I. Estimated Time Schedule*</h1>
                    <p style="line-height: 1;"><strong><span style="font-size: 14pt;"><span lang="EN-US" style="line-height: 115%;">I. Estimated Time Schedule*</span></strong></p>
                    <p><span style="font-size: 14pt;"><span lang="EN-US" style="line-height: 115%;"><span style="font-size: 12pt;">*Subject to change anytime</span></span></span></p>
                    <p class="MsoNormal" style="line-height: 1;">&nbsp;</p>
                    @foreach($schedule as $index => $schedules)
                        <p class="MsoNormal" style="line-height: 1;">
                            <strong>
                                <span style="font-size: 14pt;">
                                    <span lang="EN-US" style="line-height: 75%;">
                                        {!! $index == 0 ? 'From: Philadelphia' : 'From: JFK Airport' !!}
                                    </span>
                                </span>
                            </strong>
                        </p>
                        <p class="MsoNormal" style="line-height: 1;">
                            <strong>
                                <span style="font-size: 14pt;">
                                    <span lang="EN-US" style="line-height: 75%;">
                                        {!! $index == 0 ? 'To: JFK Airport' : 'To: Philadelphia' !!}
                                    </span>
                                </span>
                            </strong>
                        </p>

                        @php
                            $dateNormalTo = \Carbon\Carbon::createFromFormat('d-m-Y', $first_from);
                            $dateNormalAfterFrom = \Carbon\Carbon::createFromFormat('d-m-Y', $first_to);
                        @endphp

                        From <b>{{ $dateNormalTo->format('Y F jS') }}</b> To <b>{{ $dateNormalAfterFrom->format('Y F jS') }}</b>
                        <table class="MsoTableGrid" style="border-collapse: collapse; border: none; mso-border-alt: solid black .5pt; mso-yfti-tbllook: 1024; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt; mso-border-insideh: .5pt solid black; mso-border-insidev: .5pt solid black;" border="1" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes;">
                                <td style="width: 235.85pt; border: solid windowtext 1.0pt; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">LOCATION</span>
                                        </strong>
                                    </p>
                                </td>
                                <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal" style="text-align: center;">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">1st Trip</span>
                                        </strong>
                                    </p>
                                </td>
                                <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal" style="text-align: center;">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">2nd Trip</span>
                                        </strong>
                                    </p>
                                </td>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr style="mso-yfti-irow: {{ $loop->iteration }}">
                                        <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['from_master_area']))
                                                <p class="MsoNormal">
                                                    {!! '<b>' . $schedule['from_master_area'] . '</b>' !!}
                                                </p>
                                                @if(isset($schedule['from_master_sub_area']))
                                                    @foreach($schedule['from_master_sub_area'] as $subArea)
                                                        <p class="MsoNormal">{{ $subArea }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                @foreach($schedule['from_master_sub_area'] as $subArea)
                                                    <b>{{ $subArea }}</b>
                                                @endforeach
                                            @endif
                                        </td>
                                        <!-- Non DST -->
                                        <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['1st_trip']))
                                                @if(isset($schedule['from_master_area']))
                                                    <br><br>
                                                    @foreach($schedule['1st_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p><br>
                                                    @endforeach
                                                @else
                                                    @foreach($schedule['1st_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p class="MsoNormal" style="text-align: center;">-</p>
                                            @endif
                                        </td>
                                        <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['2nd_trip']))
                                                @if(isset($schedule['from_master_area']))
                                                    <br><br>
                                                    @foreach($schedule['2nd_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p><br>
                                                    @endforeach
                                                @else
                                                    @foreach($schedule['2nd_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p class="MsoNormal" style="text-align: center;">-</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if($index == 0)
                                <tr style="mso-yfti-irow: 1">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <b>Newark Airport</b>
                                    </td>
                                    <!-- first table newark first trip -->
                                    <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[0])) }}</p>
                                    </td>
                                    <!-- first table newark second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[0])) }}</p>
                                    </td>
                                </tr>
                                <tr style="mso-yfti-irow: 2">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <b>JFK Airport</b>
                                    </td>
                                    <!-- first table JFK first trip -->
                                    <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[1])) }}</p>
                                    </td>
                                    <!-- first table JFK second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[1])) }}</p>
                                    </td>
                                </tr>
                                @else
                                <tr style="mso-yfti-irow: 1">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal"><b>New Jersey</b></p>
                                        <p class="MsoNormal">511 Old Post Rd, NJ08817 (Kam Man Food)</p>
                                        <p class="MsoNormal">75 NJ Tpke, Hamilton NJ 08620, Rest Area New Jersey, T.P. Woodrow Wilson</p>
                                        <p class="MsoNormal">601 Fellowship Rd, Mt Laurel Township, NJ 08054 (Walmart)</p>
                                        
                                    </td>
                                    <!-- first table new jersey first trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 511 Old Post Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[2])) }}</p>
                                        <br>
                                        {{-- 75 NJ Tpke --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[3])) }}</p>
                                        <br>
                                        {{-- 601 Fellowship Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[4])) }}</p>
                                    </td>
                                    <!-- first table new jersey second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 511 Old Post Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[2])) }}</p>
                                        <br>
                                        {{-- 75 NJ Tpke --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[3])) }}</p>
                                        <br>
                                        {{-- 601 Fellowship Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[4])) }}</p>
                                    </td>
                                </tr>
                                <tr style="mso-yfti-irow: 2">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal"><b>Philadelphia</b></p>
                                        <p class="MsoNormal">828 Race Street PA19107</p>
                                        <p class="MsoNormal">2800 South 3rd St PA19148 (Oregon Supermarket)</p>
                                    </td>
                                    <!-- first table philadelphia first trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 828 Race Street --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[5])) }}</p>
                                        <br>
                                        {{-- Oregon Supermarket --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_morning[6])) }}</p>
                                    </td>
                                    <!-- first table philadelphia second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 828 Race Street --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[5])) }}</p>
                                        <br>
                                        {{-- Oregon Supermarket --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($first_eta_afternoon[6])) }}</p>
                                    </td>
                                </tr>                               
                                @endif
                            </tbody>
                        </table>

                        <br>

                        @php
                            $dateFrom = \Carbon\Carbon::createFromFormat('d-m-Y', $second_from);
                            $dateTo = \Carbon\Carbon::createFromFormat('d-m-Y', $second_to);
                        @endphp

                        From <b>{{ $dateFrom->format('Y F jS') }}</b> To <b>{{ $dateTo->format('Y F jS') }}</b><br>
                        <table class="MsoTableGrid" style="border-collapse: collapse; border: none; mso-border-alt: solid black .5pt; mso-yfti-tbllook: 1024; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt; mso-border-insideh: .5pt solid black; mso-border-insidev: .5pt solid black;" border="1" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes;">
                                <td style="width: 235.85pt; border: solid windowtext 1.0pt; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">LOCATION</span>
                                        </strong>
                                    </p>
                                </td>
                                <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal" style="text-align: center;">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">1st Trip</span>
                                        </strong>
                                    </p>
                                </td>
                                <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal" style="text-align: center;">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">2nd Trip</span>
                                        </strong>
                                    </p>
                                </td>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr style="mso-yfti-irow: {{ $loop->iteration }}">
                                        <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['from_master_area']))
                                                <p class="MsoNormal">
                                                    {!! '<b>' . $schedule['from_master_area'] . '</b>' !!}
                                                </p>
                                                @if(isset($schedule['from_master_sub_area']))
                                                    @foreach($schedule['from_master_sub_area'] as $subArea)
                                                        <p class="MsoNormal">{{ $subArea }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                @foreach($schedule['from_master_sub_area'] as $subArea)
                                                    <b>{{ $subArea }}</b>
                                                @endforeach
                                            @endif
                                        </td>
                                        <!-- DST -->
                                        <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['3rd_trip']))
                                                @if(isset($schedule['from_master_area']))
                                                    <br><br>
                                                    @foreach($schedule['3rd_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p><br>
                                                    @endforeach
                                                @else
                                                    @foreach($schedule['3rd_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p class="MsoNormal" style="text-align: center;">-</p>
                                            @endif
                                        </td>
                                        <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['4th_trip']))
                                                @if(isset($schedule['from_master_area']))
                                                    <br><br>
                                                    @foreach($schedule['4th_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p><br>
                                                    @endforeach
                                                @else
                                                    @foreach($schedule['4th_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p class="MsoNormal" style="text-align: center;">-</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if($index == 0)
                                <tr style="mso-yfti-irow: 1">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <b>Newark Airport</b>
                                    </td>
                                    <!-- first table newark first trip -->
                                    <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[0])) }}</p>
                                    </td>
                                    <!-- first table newark second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[0])) }}</p>
                                    </td>
                                </tr>
                                <tr style="mso-yfti-irow: 2">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <b>JFK Airport</b>
                                    </td>
                                    <!-- first table JFK first trip -->
                                    <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[1])) }}</p>
                                    </td>
                                    <!-- first table JFK second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[1])) }}</p>
                                    </td>
                                </tr>
                                @else
                                <tr style="mso-yfti-irow: 1">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal"><b>New Jersey</b></p>
                                        <p class="MsoNormal">511 Old Post Rd, NJ08817 (Kam Man Food)</p>
                                        <p class="MsoNormal">75 NJ Tpke, Hamilton NJ 08620, Rest Area New Jersey, T.P. Woodrow Wilson</p>
                                        <p class="MsoNormal">601 Fellowship Rd, Mt Laurel Township, NJ 08054 (Walmart)</p>
                                        
                                    </td>
                                    <!-- first table new jersey first trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 511 Old Post Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[2])) }}</p>
                                        <br>
                                        {{-- 75 NJ Tpke --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[3])) }}</p>
                                        <br>
                                        {{-- 601 Fellowship Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[4])) }}</p>
                                    </td>
                                    <!-- first table new jersey second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 511 Old Post Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[2])) }}</p>
                                        <br>
                                        {{-- 75 NJ Tpke --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[3])) }}</p>
                                        <br>
                                        {{-- 601 Fellowship Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[4])) }}</p>
                                    </td>
                                </tr>
                                <tr style="mso-yfti-irow: 2">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal"><b>Philadelphia</b></p>
                                        <p class="MsoNormal">828 Race Street PA19107</p>
                                        <p class="MsoNormal">2800 South 3rd St PA19148 (Oregon Supermarket)</p>
                                    </td>
                                    <!-- first table philadelphia first trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 828 Race Street --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[5])) }}</p>
                                        <br>
                                        {{-- Oregon Supermarket --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_morning[6])) }}</p>
                                    </td>
                                    <!-- first table philadelphia second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 828 Race Street --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[5])) }}</p>
                                        <br>
                                        {{-- Oregon Supermarket --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($second_eta_afternoon[6])) }}</p>
                                    </td>
                                </tr>                               
                                @endif
                            </tbody>
                        </table>
                        <br>

                        @php
                            $nextDateFrom = \Carbon\Carbon::createFromFormat('d-m-Y', $third_from);
                            $nextDateTo = \Carbon\Carbon::createFromFormat('d-m-Y', $third_to);
                        @endphp

                        From <b>{{ $nextDateFrom->format('Y F jS') }}</b> To <b>{{ $nextDateTo->format('Y F jS') }}</b><br>
                        <table class="MsoTableGrid" style="border-collapse: collapse; border: none; mso-border-alt: solid black .5pt; mso-yfti-tbllook: 1024; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt; mso-border-insideh: .5pt solid black; mso-border-insidev: .5pt solid black;" border="1" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes;">
                                <td style="width: 235.85pt; border: solid windowtext 1.0pt; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">LOCATION</span>
                                        </strong>
                                    </p>
                                </td>
                                <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal" style="text-align: center;">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">1st Trip</span>
                                        </strong>
                                    </p>
                                </td>
                                <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                    <p class="MsoNormal" style="text-align: center;">
                                        <strong>
                                            <span lang="EN-US" style="font-size: 14.0pt; line-height: 115%;">2nd Trip</span>
                                        </strong>
                                    </p>
                                </td>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr style="mso-yfti-irow: {{ $loop->iteration }}">
                                        <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['from_master_area']))
                                                <p class="MsoNormal">
                                                    {!! '<b>' . $schedule['from_master_area'] . '</b>' !!}
                                                </p>
                                                @if(isset($schedule['from_master_sub_area']))
                                                    @foreach($schedule['from_master_sub_area'] as $subArea)
                                                        <p class="MsoNormal">{{ $subArea }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                @foreach($schedule['from_master_sub_area'] as $subArea)
                                                    <b>{{ $subArea }}</b>
                                                @endforeach
                                            @endif
                                        </td>
                                        <!-- DST -->
                                        <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['5th_trip']))
                                                @if(isset($schedule['from_master_area']))
                                                    <br><br>
                                                    @foreach($schedule['5th_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p><br>
                                                    @endforeach
                                                @else
                                                    @foreach($schedule['5th_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p class="MsoNormal" style="text-align: center;">-</p>
                                            @endif
                                        </td>
                                        <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                            @if(isset($schedule['6th_trip']))
                                                @if(isset($schedule['from_master_area']))
                                                    <br><br>
                                                    @foreach($schedule['6th_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p><br>
                                                    @endforeach
                                                @else
                                                    @foreach($schedule['6th_trip'] as $time)
                                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($time)) }}</p>
                                                    @endforeach
                                                @endif
                                            @else
                                                <p class="MsoNormal" style="text-align: center;">-</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if($index == 0)
                                <tr style="mso-yfti-irow: 1">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <b>Newark Airport</b>
                                    </td>
                                    <!-- first table newark first trip -->
                                    <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[0])) }}</p>
                                    </td>
                                    <!-- first table newark second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[0])) }}</p>
                                    </td>
                                </tr>
                                <tr style="mso-yfti-irow: 2">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <b>JFK Airport</b>
                                    </td>
                                    <!-- first table JFK first trip -->
                                    <td style="width: 83.35pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[1])) }}</p>
                                    </td>
                                    <!-- first table JFK second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[1])) }}</p>
                                    </td>
                                </tr>
                                @else
                                <tr style="mso-yfti-irow: 1">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal"><b>New Jersey</b></p>
                                        <p class="MsoNormal">511 Old Post Rd, NJ08817 (Kam Man Food)</p>
                                        <p class="MsoNormal">75 NJ Tpke, Hamilton NJ 08620, Rest Area New Jersey, T.P. Woodrow Wilson</p>
                                        <p class="MsoNormal">601 Fellowship Rd, Mt Laurel Township, NJ 08054 (Walmart)</p>
                                        
                                    </td>
                                    <!-- first table new jersey first trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 511 Old Post Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[2])) }}</p>
                                        <br>
                                        {{-- 75 NJ Tpke --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[3])) }}</p>
                                        <br>
                                        {{-- 601 Fellowship Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[4])) }}</p>
                                    </td>
                                    <!-- first table new jersey second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 511 Old Post Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[2])) }}</p>
                                        <br>
                                        {{-- 75 NJ Tpke --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[3])) }}</p>
                                        <br>
                                        {{-- 601 Fellowship Rd --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[4])) }}</p>
                                    </td>
                                </tr>
                                <tr style="mso-yfti-irow: 2">
                                    <td style="width: 235.85pt; border: solid windowtext 1.0pt; border-top: none; padding: 0cm 5.4pt;" valign="top">
                                        <p class="MsoNormal"><b>Philadelphia</b></p>
                                        <p class="MsoNormal">828 Race Street PA19107</p>
                                        <p class="MsoNormal">2800 South 3rd St PA19148 (Oregon Supermarket)</p>
                                    </td>
                                    <!-- first table philadelphia first trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 828 Race Street --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[5])) }}</p>
                                        <br>
                                        {{-- Oregon Supermarket --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_morning[6])) }}</p>
                                    </td>
                                    <!-- first table philadelphia second trip -->
                                    <td style="width: 82.8pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; padding: 0cm 5.4pt;" valign="top">
                                        <br>
                                        <br>
                                        {{-- 828 Race Street --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[5])) }}</p>
                                        <br>
                                        {{-- Oregon Supermarket --}}
                                        <p class="MsoNormal" style="text-align: center;">{{ date('h:i A', strtotime($third_eta_afternoon[6])) }}</p>
                                    </td>
                                </tr>                               
                                @endif
                            </tbody>          
                        </table>
                        <br>
                    @endforeach
                    <p>Note: Schedule subject to change anytime</p>
                    
                    <p style="font-size: 18pt;">
                        <strong>II. Pick up / drop off location, address and link:</strong>
                    </p>
                    
                    <p>
                        <strong>A. Philadelphia</strong><br>
                        1. Oregon market PA 19148,<br>
                        Link: <a href="https://maps.app.goo.gl/T8EdbR3bABmmXVHf8">https://maps.app.goo.gl/T8EdbR3bABmmXVHf8</a><br>
                        2. 828 Race Street, PA 19107<br>
                        Link: <a href="https://maps.app.goo.gl/qS9b5D63MZkhn53e7">https://maps.app.goo.gl/qS9b5D63MZkhn53e7</a>
                    </p>
                    
                    <p>
                        <strong>B. New Jersey</strong><br>
                        1. Walmart - 601 Fellowship Road, Mt Laurel Township, NJ 08054<br>
                        Link: <a href="https://maps.app.goo.gl/LKgkJn6dmGNog4WD9">https://maps.app.goo.gl/LKgkJn6dmGNog4WD9</a><br>
                        2a. (Pick up location only) Rest Area New Jersey, T.P. Woodrow Wilson 75 NJ Tpke, Hamilton NJ 08620<br>
                        Location link: <a href="https://maps.app.goo.gl/8iR8uFUeW781ssdv9">https://maps.app.goo.gl/8iR8uFUeW781ssdv9</a><br>
                        2b. (Drop off location only) 58 7 NJ Tpke, Hamilton Township, NJ 08691, USA<br>
                        Location link: <a href="https://maps.app.goo.gl/44LcVeVu3CEZdHPU6">https://maps.app.goo.gl/44LcVeVu3CEZdHPU6</a><br>
                        3. Edison New Jersey, Kam Man Food 511 Old Post Road, Edison New Jersey 08817<br>
                        Location link: <a href="https://maps.app.goo.gl/8iR8uFUeW781ssdv9">https://maps.app.goo.gl/8iR8uFUeW781ssdv9</a>
                    </p>
                    
                    <p>
                        <strong>C. Newark Airport, New Jersey. Pick up location:</strong><br>
                        Important note: Please activate your WhatsApp and contact us. There is a free Wi-Fi connection available at the airport terminal if needed.<br>
                        1. Terminal A, level 1 Passenger Pick up (confirm in advance)<br>
                        2. Terminal B, level 1 Passenger Pick up (confirm in advance)<br>
                        3. Terminal C, level 1 Passenger Pick up (confirm in advance)
                    </p>
                    
                    <p>
                        <strong>D. JFK Airport, New York</strong><br>
                        Important note: Please activate your WhatsApp and contact us. There is a free Wi-Fi connection available at the airport terminal if needed.<br>
                        1. Terminal 1, across yellow cab taxi (confirm in advance)<br>
                        2. Terminal 2, cross to the centre isle, passenger pick up area A (confirm in advance)<br>
                        3. Terminal 3, cross to the centre isle, passenger pick up area A (confirm in advance)<br>
                        4. Terminal 4, cross to the centre isle, passenger pick up area A (confirm in advance)<br>
                        5. Terminal 5, cross to the centre isle, passenger pick up area (confirm in advance)<br>
                        6. Terminal 6, cross to the centre isle, passenger pick up area (confirm in advance)<br>
                        7. Terminal 7, cross to the centre isle, passenger pick up area (confirm in advance)<br>
                        8. Terminal 8, cross to the centre isle, passenger pick up area (confirm in advance)
                    </p>
                                    </div>
            </div>
        </div>
    </section>
@endsection

@section('vitamin')
@endsection
