import { Component, Input, OnInit } from '@angular/core';
import { NgFor, NgIf } from '@angular/common';

import { GenericService } from '../generic.service';
import { Product } from '../model/product';
import { Category } from '../model/category';

@Component({
    selector: 'app-products',
    imports: [NgFor, NgIf],
    templateUrl: './products.component.html',
    styleUrl: './products.component.css'
})
export class ProductsComponent implements OnInit {
    products : Product[] = [];
    categories: Category[] = [];
    selectedCategory?: Category;

    constructor(private genericService : GenericService) {}

    ngOnInit(): void {
        this.getCategories();
    }

    getCategories(): void {
        this.genericService.fetchCategories()
            .subscribe(categories => this.categories = categories);
    }

    onSelect(category: Category): void {
        console.log("selected " + category.name);
        if (this.selectedCategory == category) {
            this.selectedCategory = undefined;
        } else {
            this.selectedCategory = category;
        }
        this.getProductsByCategory(this.selectedCategory);
    }

    getProductsByCategory(category?: Category): void {
        if (category === undefined) {
            this.products = [];
            return;
        }
        let page: number = 1;
        this.genericService.fetchProducts(category, page)
            .subscribe(products => this.products = products);
    }
}
