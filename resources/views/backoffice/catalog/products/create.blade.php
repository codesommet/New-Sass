<?php $page = 'add-product'; ?>
@extends('backoffice.layout.mainlayout')
@section('content')
    <!-- ========================
            Start Page Content
        ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- start row -->
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6><a href="{{ route('bo.catalog.products.index') }}"><i class="isax isax-arrow-left me-2"></i>Produits</a></h6>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">Informations de base</h6>
                                <form action="{{ route('bo.catalog.products.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <span class="text-gray-9 fw-bold mb-2 d-flex">Image du produit</span>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0">
                                                <i class="isax isax-image text-primary fs-24"></i>
                                            </div>
                                            <div class="d-inline-flex flex-column align-items-start">
                                                <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                    <i class="isax isax-image me-1"></i>Importer une image
                                                    <input type="file" class="form-control image-sign @error('product_image') is-invalid @enderror" name="product_image" accept="image/*">
                                                </div>
                                                <span class="text-gray-9 fs-12">Format JPG ou PNG, ne d&eacute;passant pas 5 Mo.</span>
                                                @error('product_image')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-label">Type d'article<span class="text-danger ms-1">*</span></label>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="form-check me-3">
                                            <input class="form-check-input @error('item_type') is-invalid @enderror" type="radio" name="item_type" id="item-type-product"
                                                value="product" {{ old('item_type', 'product') === 'product' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="item-type-product">
                                                Produit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input @error('item_type') is-invalid @enderror" type="radio" name="item_type" id="item-type-service"
                                                value="service" {{ old('item_type') === 'service' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="item-type-service">
                                                Service
                                            </label>
                                        </div>
                                        @error('item_type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- start row -->
                                    <div class="row gx-3">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom<span
                                                        class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Code<span
                                                        class="text-danger ms-1">*</span></label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}">
                                                    @error('code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">SKU</label>
                                                <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') }}">
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cat&eacute;gorie<span
                                                        class="text-danger ms-1">*</span></label>
                                                <select class="select @error('category_id') is-invalid @enderror" name="category_id">
                                                    <option value="">S&eacute;lectionner</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prix de vente<span
                                                        class="text-danger ms-1">*</span></label>
                                                <input type="number" step="0.01" min="0" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') }}">
                                                @error('selling_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prix d'achat</label>
                                                <input type="number" step="0.01" min="0" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" value="{{ old('purchase_price') }}">
                                                @error('purchase_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Quantit&eacute;</label>
                                                <input type="number" step="1" min="0" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}">
                                                @error('quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Unit&eacute;</label>
                                                <select class="select @error('unit_id') is-invalid @enderror" name="unit_id">
                                                    <option value="">S&eacute;lectionner</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                                                    @endforeach
                                                </select>
                                                @error('unit_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type de remise</label>
                                                <select class="select @error('discount_type') is-invalid @enderror" name="discount_type">
                                                    <option value="none" {{ old('discount_type', 'none') === 'none' ? 'selected' : '' }}>Aucune</option>
                                                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                                                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixe</option>
                                                </select>
                                                @error('discount_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Valeur de la remise</label>
                                                <input type="number" step="0.01" min="0" class="form-control @error('discount_value') is-invalid @enderror" name="discount_value" value="{{ old('discount_value') }}">
                                                @error('discount_value')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Code-barres</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" value="{{ old('barcode') }}">
                                                    @error('barcode')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Quantit&eacute; d'alerte</label>
                                                <input type="number" step="1" min="0" class="form-control @error('alert_quantity') is-invalid @enderror" name="alert_quantity" value="{{ old('alert_quantity') }}">
                                                @error('alert_quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cat&eacute;gorie de taxe</label>
                                                <select class="select @error('tax_category_id') is-invalid @enderror" name="tax_category_id">
                                                    <option value="">S&eacute;lectionner</option>
                                                    @foreach($taxCategories as $taxCategory)
                                                        <option value="{{ $taxCategory->id }}" {{ old('tax_category_id') == $taxCategory->id ? 'selected' : '' }}>{{ $taxCategory->name }} ({{ $taxCategory->rate }}%)</option>
                                                    @endforeach
                                                </select>
                                                @error('tax_category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Devise</label>
                                                <select class="select @error('currency') is-invalid @enderror" name="currency">
                                                    <option value="">S&eacute;lectionner</option>
                                                    @foreach($currencies as $curr)
                                                        <option value="{{ $curr->code }}" {{ old('currency') == $curr->code ? 'selected' : '' }}>{{ $curr->name }} ({{ $curr->symbol }})</option>
                                                    @endforeach
                                                </select>
                                                @error('currency')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3 d-flex flex-column justify-content-end h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="track_inventory" id="track-inventory" value="1" {{ old('track_inventory') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="track-inventory">Suivre l'inventaire</label>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="mb-3 d-flex flex-column justify-content-end h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="is-active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is-active">Actif</label>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Description du produit</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="{{ route('bo.catalog.products.index') }}" class="btn btn-outline-white">Annuler</a>
                                        <button type="submit" class="btn btn-primary">Cr&eacute;er</button>
                                    </div>

                                </form>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->

        </div>
        <!-- End Content -->

    </div>

    <!-- ========================
            End Page Content
        ========================= -->
@endsection
