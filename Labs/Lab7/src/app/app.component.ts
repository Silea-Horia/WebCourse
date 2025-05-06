import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { ProductsComponent } from "./products/products.component";
import { CategoriesComponent } from "./categories/categories.component";

@Component({
  selector: 'app-root',
  imports: [CategoriesComponent, ProductsComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'ecommerce';
}
