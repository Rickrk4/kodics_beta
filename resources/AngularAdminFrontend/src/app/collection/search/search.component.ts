import { Component, OnInit, Output, EventEmitter, Input } from '@angular/core';
import { ComicService } from 'src/app/comic/comic.service';

import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';
import { Observable, Subject } from 'rxjs';
import { CollectionService } from '../collection.service';
import { SearchService } from './search.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent implements OnInit {
  //test: any;
  comics$: any;
  //comics: any;

  @Input() url: string;
  @Input() placeholder: string;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  terms: string;
  private searchTerms: Subject<string> = new Subject<string>();

  constructor(private comicService: SearchService) { }

  search(terms: string): void {
    this.searchTerms.next(terms);
    this.terms = terms;
  }

  select(id: number) {
    this.comics$ = null;
    this.search('');
    this.selected.emit(id);

  }



  ngOnInit() {

    this.searchTerms.pipe(
      debounceTime(300),
      distinctUntilChanged(),
      switchMap((term: string) => this.comicService.search(this.url, term))
    ).subscribe(
      comics => this.comics$ = comics
    );
/*
    this.comicService.searchComics('').subscribe(
      test => this.test = test
    )
*/
  }

}
