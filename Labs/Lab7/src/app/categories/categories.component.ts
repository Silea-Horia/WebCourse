import { Component } from '@angular/core';
import { NgFor } from '@angular/common';

import { GenericService } from '../generic.service';
import { Category } from '../model/category';

@Component({
  selector: 'app-categories',
  imports: [NgFor],
  templateUrl: './categories.component.html',
  styleUrl: './categories.component.css'
})
export class CategoriesComponent {
    categories: Category[] = [];

    constructor(private genericService : GenericService) {}

    ngOnInit(): void {
        this.getCategories();
    }

    getCategories(): void {
        this.genericService.fetchCategories()
            .subscribe(categories => this.categories = categories);
    }
}
