import { Injectable } from '@angular/core';
import { Option } from './resources/option';
import { Observable, of } from 'rxjs';
import { SETTINGS } from './settings-mock';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class SettingsService {


  constructor(private httpClient: HttpClient) { }

  getOptions(): Observable<any>{
    //Solo in build
    return this.httpClient.get<any>('admin');
    return of(SETTINGS);
  }
  getOption(opt: string): Observable<any>{
    return this.httpClient.get<any>('admin/' + opt);
  }

  setOption(opt: string, newValue: any): Observable<any>{
    return this.httpClient.put<any>('admin/' + opt, {
      value: newValue
    });
  }

}
