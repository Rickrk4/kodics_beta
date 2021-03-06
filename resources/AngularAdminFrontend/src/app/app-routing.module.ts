import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SettingsComponent } from './settings/settings.component';
import { CollectionComponent } from './collection/collection.component';
import { ComicComponent } from './comic/comic.component';
import { HomeAdminComponent } from './home-admin/home-admin.component';


const routes: Routes = [
  { path: 'ad', redirectTo:'admin/home', pathMatch: 'full'},
  { path: 'admin', redirectTo:'admin/home', pathMatch: 'full'},
  { path: 'admin/home', component:  HomeAdminComponent},
  { path: 'admin/settings', component: SettingsComponent },
  { path: 'admin/collections', component: CollectionComponent},
  { path: 'admin/comics', component: ComicComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
