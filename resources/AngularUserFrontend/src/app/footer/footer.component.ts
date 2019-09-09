import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css']
})
export class FooterComponent implements OnInit {

  @Input() current: number;
  @Input() first: number = 1;
  @Input() last: number;
  @Output() next: EventEmitter<any> = new EventEmitter();
  @Output() prev: EventEmitter<any> = new EventEmitter();

  constructor() { }

  ngOnInit() {  }

  nextPage(): void{
    this.next.emit();
  }

  prevPage(): void{
    this.prev.emit();
  }

}
