$url = "http://192.168.1.100:8082/current"


    $a = Get-Date -Format yyyy_MM_dd_HH_mm_ss
    "Time: " + $a

    $buffer = "C:\Users\vales\github\visual-MTC\local\current.xml"
    $output = "C:\Users\vales\github\visual-MTC\local\output.txt"

    #Remove-Item $buffer
    #Remove-Item $output

    $start_time = Get-Date

    Invoke-WebRequest -Uri $url -OutFile $buffer

    ## casting the file text to an XML object
    [xml]$xml = Get-Content -Path $buffer

    $xml.MTConnectStreams.Streams.DeviceStream.ComponentStream.Events.Program | Out-File $output

    Write-Output "Time taken: $((Get-Date).Subtract($start_time).Seconds) second(s)"




