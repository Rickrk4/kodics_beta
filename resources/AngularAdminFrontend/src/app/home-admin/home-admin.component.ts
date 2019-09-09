import { Component, OnInit } from '@angular/core';
import { ScannerService } from '../scanner.service';
import { Observable, interval } from 'rxjs';
import { SettingsService } from '../settings/settings.service';

@Component({
  selector: 'app-home-admin',
  templateUrl: './home-admin.component.html',
  styleUrls: ['./home-admin.component.css']
})
export class HomeAdminComponent implements OnInit {
  scanJob: any;
  scanId: number;
  scanning: boolean = false;
  scanFrequency: any;
  showScanFrequencyes: boolean = false;
  constructor(private scannerService: ScannerService, private settingsService: SettingsService) { }

  getScanFrequency(): void{
    this.settingsService.getOption('scan_frequency').subscribe(
      scanFrequency => this.scanFrequency = scanFrequency
    );
  }

  setScanFrequency(scanFrequency: string){
    this.showScanFrequencyes = false;
    this.settingsService.setOption('scan_frequency.value', scanFrequency).subscribe();
    this.scanFrequency.value = scanFrequency;

  }

  scanStarted(): void {
    this.scannerService.scanJob().subscribe(
      scanning => {
        if ( scanning > 0 ) {
          this.scanning = true;
          this.scanStatus(scanning);
        } else {
          this.scanning = false;
        }
      }
    )
  }

  scanStatus(id): void{
    this.scannerService.scanStatus(id).subscribe(
      scanJob => this.scanJob = scanJob
    );
    if(this.scanJob.status == 'finished'){
      this.scanning = false;
      this.scanJob = null;
    }
  }

  scan(): void {
    this.scannerService.scan().subscribe(
      scanJob => this.scanJob = scanJob
    );
    this.scanning = true;
  }


  ngOnInit() {
    interval(1000).subscribe(
      () => (
        this.scanning ? this.scanStatus(this.scanJob.id) : this.scanStarted()
      )
    )
    this.getScanFrequency();
  }

}
