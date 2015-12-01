<html>

    <head>
        <style type="text/css">
            thead th {
                text-align: center;
                vertical-align: middle;
                wrap-text: true;
                font-size: 10;
                height: 20px;
            }
            th {
                width: 25px;
            }
            .two-rows {
                
            }
            .wide {
                width: 50px;
            }
            tr.tall-row th {
                height: 60px;
            }
            .blue {
                background-color: #6fa8dc;
                color: #ffffff;
            }
            .yellow {
                background-color: #ffff00;
            }
            .green {
                background-color: #00ff00;
            }
            .orange {
                background-color: #ff9900;
            }
            .peach {
                background-color: #f9cb9c;
            }
            .olive {
                background-color: #93c47d;
            }
            .purple {
                background-color: #7030a0;
                color: #ffffff;
            }
        </style>
    </head>

    @if(!empty($output_data))
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="blue two-rows">Type:<br />Person/Place/<br />Project/Event/<br />NetworkEd Event</th>
                <th rowspan="2" class="blue two-rows">Name</th>
                <th colspan="5" class="blue">Physical address</th>
                <th colspan="5" class="blue">Details</th>
                <th rowspan="2" class="blue two-rows wide">Description</th>
                <th colspan="6" class="blue">Tags</th>
                <th rowspan="2" class="blue two-rows">Added by User</th>
                <th rowspan="2" class="blue two-rows">Link</th>
            </tr>
            <tr class="tall-row">
                <th></th>
                <th></th>
                <th class="yellow">Location name</th>
                <th class="yellow">Street address</th>
                <th class="yellow">City</th>
                <th class="yellow">State</th>
                <th class="yellow">Zip</th>
                <th class="olive">Date/Time</th>
                <th class="olive">Contact name</th>
                <th class="olive">Email address</th>
                <th class="olive">Phone number</th>
                <th class="olive">Website</th>
                <th class="wide"></th>
                <th class="purple">Education Level</th>
                <th class="purple">Subjects</th>
                <th class="purple">Technology Related</th>
                <th class="purple">Community Resources</th>
                <th class="purple">Research Disciplines</th>
                <th class="purple">Other</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($output_data as $row)
            <tr>
                <td>[[ $row['type'] ]]</td>
                <td>[[ $row['title'] ]]</td>
                <td>[[ $row['location'] ]]</td>
                <td>[[ $row['address'] ]]</td>
                <td>[[ $row['city'] ]]</td>
                <td>[[ $row['state'] ]]</td>
                <td>[[ $row['zip'] ]]</td>
                <td>@if($row['date']) [[ date('m/d/Y h:m A', strtotime($row['date'])) ]] @endif</td>
                <td>[[ $row['contact_name'] ]]</td>
                <td><a href="mailto:[[ $row['contact_email'] ]]">[[ $row['contact_email'] ]]</a></td>
                <td>[[ $row['contact_phone'] ]]</td>
                <td><a href="[[ $row['contact_website'] ]]">[[ $row['contact_website'] ]]</a></td>
                <td class="wide">[[ $row['description'] ]]</td>
                <td><?php echo implode($row['tags'][0], ', '); ?></td>
                <td><?php echo implode($row['tags'][1], ', '); ?></td>
                <td><?php echo implode($row['tags'][2], ', '); ?></td>
                <td><?php echo implode($row['tags'][3], ', '); ?></td>
                <td><?php echo implode($row['tags'][4], ', '); ?></td>
                <td><?php echo implode($row['tags'][5], ', '); ?></td>
                <td>[[ $row['user'] ]]</td>
                <td><a href="https://wcernetwork.org/#!/post/[[ $row['id'] ]]">https://wcernetwork.org/#!/post/[[ $row['id'] ]]</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</html>