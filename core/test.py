#!"C:\Python27\python.exe"

"""
  Partition Problem, ok for small sequences
"""
# ===========================================================================
def greedyPartition(S, X=None):
  A, B = [], []
  if X == None:
    T = S
  else:
    T = []
    for sx,sv in enumerate(S):
      if sx in X:
        A.append(sv)
      else:
        T.append(sv)
  T.sort()
  for t in T:
    if sum(A) <= sum(B):
      A.append(t)
    else:
      B.append(t)

  diff = abs(sum(A) - sum(B))
  return diff, A, B

# ---------------------------------------------------------------------------
def greedierPartition(S):
  """ Create starter of all pairs from S, there are sz*(sz-1)/2 cases,
      pick the best one.
   """
  sz = len(S)
  minDiff = 99999999999
  minA    = None
  minB    = None
  for x in range(0,sz-1):
    for y in range(x+1,sz):
      d,a,b = greedyPartition(S, X=(x,y))
      if d == 0:
        return d,a,b
      if d < minDiff:
        minDiff = d
        minA    = a
        minB    = b
  return minDiff, minA, minB

# ===========================================================================
if __name__ == "__main__":
  caseList = [
    [100, 5, 4, 8, 1, 2, 2,3],
  ]
  for testCase in caseList:
    diff, a, b = greedierPartition(testCase)
    print 'Sequence: {}'.format(testCase)
    print '  diff {}: {}'.format(diff, (a,b))
