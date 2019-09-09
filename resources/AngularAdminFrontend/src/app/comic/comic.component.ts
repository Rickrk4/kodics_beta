import { Component, OnInit } from '@angular/core';
import { ComicService } from './comic.service';

@Component({
  selector: 'app-comic',
  templateUrl: './comic.component.html',
  styleUrls: ['./comic.component.css']
})
export class ComicComponent implements OnInit {

  comics: any;

  getComics(): void {
    this.comicService.getData().subscribe(
      comics => this.comics = comics
    );
  }

  constructor(private comicService: ComicService) { }

  ngOnInit() {
    this.getComics();
  }

}
