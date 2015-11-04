<div class="table-responsive">
    @foreach ($reportData as $centerName => $coursesData)
        <br/>
        <h3>{{ $centerName }}</h3>
        <table class="table table-condensed table-striped table-hover">
            <thead>
            <tr>
                <th style="border-top: 2px solid #DDD;">&nbsp;</th>
                <th style="border-top: 2px solid #DDD;">&nbsp;</th>
                <th style="border-top: 2px solid #DDD; border-right: 2px solid #DDD;">&nbsp;</th>
                <th colspan="3" style="border-top: 2px solid #DDD; border-right: 2px solid #DDD; text-align: center">
                    Quarter Starting
                </th>
                <th colspan="3" style="border-top: 2px solid #DDD; border-right: 2px solid #DDD; text-align: center">
                    Current
                </th>
                <th colspan="5" style="border-top: 2px solid #DDD; text-align: center">Completion</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>Location</th>
                <th style="border-right: 2px solid #DDD;">Date</th>
                <th style="text-align: center">TER</th>
                <th style="text-align: center">Standard Starts</th>
                <th style="border-right: 2px solid #DDD; text-align: center">Xfer</th>
                <th style="text-align: center">TER</th>
                <th style="text-align: center">Standard Starts</th>
                <th style="border-right: 2px solid #DDD; text-align: center">Xfer</th>
                <th style="text-align: center">Standard Starts</th>
                <th style="text-align: center">Potentials</th>
                <th style="border-right: 2px solid #DDD; text-align: center">Registrations</th>
                <th style="text-align: center">Reg Fulfillment</th>
                <th style="text-align: center">Reg Effectiveness</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($coursesData as $courseData)
                <tr>
                    <td>{{ $courseData['type'] }}</td>
                    <td>{{ $courseData['location'] }}</td>
                    <td style="vertical-align: middle; border-right: 2px solid #DDD;">{{ $courseData['startDate']->format("M j, Y") }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['quarterStartTer'] }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['quarterStartStandardStarts'] }}</td>
                    <td style="vertical-align: middle; border-right: 2px solid #DDD; text-align: center">{{ $courseData['quarterStartXfer'] }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['currentTer'] }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['currentStandardStarts'] }}</td>
                    <td style="vertical-align: middle; border-right: 2px solid #DDD; text-align: center">{{ $courseData['currentXfer'] }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['completedStandardStarts'] }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['potentials'] }}</td>
                    <td style="vertical-align: middle; border-right: 2px solid #DDD; text-align: center">{{ $courseData['registrations'] }}</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['completionStats']['registrationFulfillment'] }}%</td>
                    <td style="vertical-align: middle; text-align: center">{{ $courseData['completionStats']['registrationEffectiveness'] }}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
</div>