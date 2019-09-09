import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { Resources } from '../resources.enum';
import { HttpClient } from '@angular/common/http';
import { Resource } from './resources/resource';
import { ResourceMock } from './test/resource-mock';


@Injectable({
  providedIn: 'root'
})
export class LibraryService {


  private resourceMock: ResourceMock = new ResourceMock();

  constructor(private httpClient: HttpClient) { }
  // tslint:disable-next-line: no-inferrable-types
  //private url: string = 'localhost:8000/';

  getData(type: string, id: string): Observable<any>{

    // return of(this.resourceMock.getDir());

    return this.httpClient.get<any>( Resources[type] + id );
  }

  get(url: string): Observable<any>{
    //return of(url);
    return this.httpClient.get<any>(url);
  }

  search(terms: string): Observable<any> {
    console.log("search for" + terms);
    return this.httpClient.get<any>('c/?' + terms);
  }

}

