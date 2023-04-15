import { useI18n as baseUseI18n } from 'vue-i18n'
import { getCurrentInstance } from 'vue'

export function useI18n() {
  const i18n = baseUseI18n()

  const getInstance = () => {
    const vm = getCurrentInstance()
    if (!vm) {
      throw new Error('useI18n() must be called within a component')
    }
    return vm
  }

  const t = (key, values) => {
    const instance = getInstance()
    return i18n.t(key, values, { ...instance.proxy.$attrs })
  }

  return {
    ...i18n,
    t
  }
}