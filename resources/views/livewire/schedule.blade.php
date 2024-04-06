<div class="flex flex-col">
    <div class="-my-2 sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow border-b border-gray-200 sm:rounded-lg">
                <div class="px-6 py-3 bg-purple-300 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    L'emploi du temps de {{$child->name}}
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="text-center">
                        <th></th>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                        <th>Samedi</th>
                        <th>Dimanche</th>
                        </thead>
                        <tbody>
                        @php
                            $cellCount = 0;
                        @endphp

                        @foreach (range(8, 17) as $hour)
                            <tr>
                                <td>{{ sprintf('%02d:00 - %02d:00', $hour, $hour+1) }}</td>
                                @foreach (range(1, 7) as $day)
                                    @if (isset($timetable[$day][$hour]))
                                        @foreach ($timetable[$day][$hour] as $session)
                                            @if ($cellCount < 80)
                                                <td class="align-middle text-center bg-indigo-200" rowspan="{{ $session['duration'] }}">
                                                    <div class="mb-2">{{ $session['title'] }}</div><br>
                                                    <div class="mb-2">{{ $session['teacher'] }}</div><br>
                                                    <div class="mb-2">({{ $session['start_time'] }} - {{ $session['end_time'] }})</div>
                                                </td>
                                                @php
                                                    $cellCount += $session['duration'];
                                                @endphp
                                            @endif
                                        @endforeach
                                    @else
                                        @if ($cellCount < 80)
                                            <td></td>
                                            @php
                                                $cellCount++;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
