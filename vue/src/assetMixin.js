export const assetMixin = {
    created() {
      window._asset = "{{ asset('') }}"
    }
  }
