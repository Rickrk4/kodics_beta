import { Injectable } from '@angular/core';
import { Resource } from 'src/app/library/resources/resource';

@Injectable({
  providedIn: 'root'
})
export class DetailService {

  private showBool: boolean = false;
  resource: any;

  constructor() { }

  showDetail(resource: Resource): void{
    this.resource = resource;
    this.showBool = true;
  }

  show(): boolean{
    return this.showBool;
  }

  close(): void{
    this.showBool = false;
  }

}
