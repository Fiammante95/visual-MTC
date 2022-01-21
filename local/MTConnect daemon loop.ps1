$url = "http://192.168.1.100:8082/current"
$continue = $true
while ($continue) {
  if ([console]::KeyAvailable) {
        Write-Output "Exit with `"q`"";
        $x = [System.Console]::ReadKey()

        switch ( $x.key)  {
            q { $continue = $false }
        }
  } else {
    $a = Get-Date -Format yyyy_MM_dd_HH_mm_ss
    "Time: " + $a

    $buffer = "current.xml"
    $output = "output.txt"

    #Remove-Item $buffer
    #Remove-Item $output

    $start_time = Get-Date

    Invoke-WebRequest -Uri $url -OutFile $buffer

    ## casting the file text to an XML object
    [xml]$xml = Get-Content -Path $buffer

    $xml.MTConnectStreams.Streams.DeviceStream.ComponentStream.Events.Program | Out-File $output

    Write-Output "Time taken: $((Get-Date).Subtract($start_time).Seconds) second(s)"

    Start-Sleep -Milliseconds 1000
  }
}


