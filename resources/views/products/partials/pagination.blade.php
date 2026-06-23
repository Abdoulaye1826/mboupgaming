@if($products->hasPages())
  <div class="p-3 border-top">{{ $products->links() }}</div>
@endif
