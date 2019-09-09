import { Component, OnInit } from '@angular/core';
import { Option } from './resources/option';
import { SettingsService } from './settings.service';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.css']
})
export class SettingsComponent implements OnInit {

  options: any;
  toggleVar: boolean = false;

  constructor(
    private settingsService: SettingsService
    ) { }

  getData(): void {
    this.settingsService.getOptions().subscribe(options => this.options = options);
  }



  isArray(obj: object): boolean {
    return Array.isArray(obj);
  }

  isString(str: any): boolean {
    return typeof str === 'string';
  }

  toggle(): void {
    this.toggleVar = !this.toggleVar;
  }

  ngOnInit() {
    this.getData();
  }

}
