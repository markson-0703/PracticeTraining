// 验证姓名是否为汉字
export function isCharacter (rule, value, callback) {
  const reg = /^[\u2E80-\u9FFF]+$/ // Unicode编码中的汉字范围
  if (value && value.length > 0) {
    if (!reg.test(value)) {
      return callback(new Error('姓名输入不合法'))
    } else {
      return callback()
    }
  } else {
    return callback(new Error('姓名输入不合法'))
  }
}
//验证是否为正整数
export function isInteger(rule, value, callback){
  if (!Number(value)) {
    return callback(new Error('请输入正整数'));
  }
}
// 验证是否为空
export function isNull (rule, value, callback) {
  if (!value) {
    return callback(new Error('输入不可以为空'))
  }
}
// 验证学号为负整数（正整数+0）
export function isSno (rule, value, callback) {
  const reg = /^\d+$/
  if (reg.test(value)) {
    return callback()
  }
  return callback(new Error('输入的学号有误'))
}
// 验证邮箱
export function isEmail (rule, value, callback) {
  if (value === '') {
    return callback(new Error('邮箱不能为空'))
  } else {
    const reg = /^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/
    if (!reg.test(value)) {
      return callback(new Error('请输入有效的邮箱'))
    } else {
      return callback()
    }
  }
}
// 验证手机号
export function isPhone (rule, value, callback) {
  const reg = /^[1][3-8][0-9]{9}$/
  if (reg.test(value) === false) {
    return callback(new Error('请输入正确的手机号'))
  } else {
    return callback()
  }
}
