import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ScannerService {

  constructor(private httpClient: HttpClient) { }

  scanJob(): Observable<any>{
    return this.httpClient.get<any>('scanJob');
  }

  scanStatus(id: number): Observable<any>{
    return this.httpClient.get<any>('scanJob/' + id);
  }

  scan(): Observable<any>{
    return this.httpClient.get<any>('scan');
  }

}
